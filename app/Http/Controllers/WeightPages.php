<?php

namespace App\Http\Controllers;

use App\Jobs\SyncRecordToRemote;
use App\Models\Detail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WeightPages extends Controller
{
    public function print(Request $request)
    {
        if (!Auth::check()) {
            return redirect(route('login.form'))->with('error', 'Authenticated user not found');
        }

        $validator = Validator::make($request->all(), [
            'serial' => 'required|exists:details,id|string',
        ], [
            'serial.exists' => 'No valid record was found',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $data = $validator->validated();

        $record = Detail::where('id', '=', $data['serial'])->first();

        if ($record) {
            return response()->json([
                'success' => true,
                'message' => 'Please Wait',
                'redirect' => route('print.layout', ['id' => $record->id])
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Recieved Data',
        ]);
    }

    public function fetchRecord(Request $request)
    {
        if (!Auth::check()) {
            return redirect(route('login.form'))->with('error', 'You are not authorized to perform this action');
        }

        $validator = Validator::make($request->all(), [
            'serial' => 'required|string|exists:details,id',
        ], [
            'serial.exists' => 'No record was found',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $record = Detail::where('id', $request->serial)->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'No record was found',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Record found!',
            'record' => $record,
        ]);
    }

    public function savefirst(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('error', 'Not allowed to perform this action');
        }

        $validator = Validator::make($request->all(), [
            'serial' => 'required|unique:details,id|string',
            'vehicleNumber' => 'required|string|max:15',
            'partyName' => 'required|string|max:20',
            'firstWeight' => 'required|string|max:15',
            'amount' => 'required|string|max:5',
            'description' => 'nullable|string|max:25',
        ], [
            'serial.unique' => 'This is already used serial',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $data = $validator->validated();
        $now = Carbon::now();

        $record = Detail::create([
            'vehicle_number' => $data['vehicleNumber'],
            'party' => Str::title($data['partyName']),
            'first_weight' => $data['firstWeight'],
            'first_date' => $now,
            'amount' => $data['amount'],
            'description' => Str::title($data['description']),
            'created_by' => Auth::user()->username,
        ]);

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Record not saved',
            ]);
        }

        // SyncRecordToRemote::dispatch($request->all(), 1);

        return response()->json([
            'success' => true,
            'message' => 'Saved Successfully',
            'redirect' => route('print.layout', ['id' => $data['serial']]),
        ]);
    }

    public function savesecond(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('error', 'You are not allowed to access this page');
        }

        $validator = Validator::make($request->all(), [
            'serial' => 'required|string|exists:details,id',
            'secondWeight' => 'required|string|max:15',
            'Description' => 'nullable|string|max:25',
        ], [
            'serial.exists' => 'No first record was found',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $data = $validator->validated();

        $record = Detail::where('id', '=', $data['serial'])->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found',
            ]);
        }

        $record->second_weight = $data['secondWeight'];
        $record->description = Str::title($data['Description']);

        $net = null;

        if ((int) $record->second_weight > (int) $record->first_weight) {
            $net = $record->second_weight - $record->first_weight;
        } else {
            $net = $record->first_weight - $record->second_weight;
        }

        $record->net_weight = $net;

        $record->second_date = Carbon::now();

        $record->save();

        // SyncRecordToRemote::dispatch($record->toArray(), 2);

        return response()->json([
            'success' => true,
            'message' => 'Record updated successfully',
            'redirect' => route('print.layout', ['id' => $data['serial']]),
        ]);
    }

    public function savenewuser(Request $request)
    {
        if (!Auth::check() && Auth::user()->role !== 'admin') {
            return redirect()->route('login.form')->with('error', 'Session not found');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:25',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:12',
            'role' => 'required|in:operator,admin',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }
        $data = $validator->validated();

        User::create([
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'name' => Str::title($data['name']),
            'role' => $data['role'],
            'phone' => $data['phone'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully!',
        ]);
    }

    public function deleteRecord(Request $request)
    {
        if (!Auth::check() && Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Only admin is allowed to delete the record!',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'serial' => 'required|exists:details,id',
        ], [
            'serial.exists' => 'No record was found to show',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $data = $validator->validated();

        $record = Detail::where('id', '=', $data['serial'])->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'No record was found',
            ]);
        }

        $record->delete();

        return response()->json([
            'success' => true,
            'message' => 'Record deleted successfully',
        ]);
    }

    public function updateRecord(Request $request)
    {
        if (!Auth::check() && Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Admin is allowed to edit this record'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'serial' => 'required|exists:details,id',
            'vehicle_number' => 'required|string|max:15',
            'party' => 'required|string|max:20',
            'first_weight' => 'required|string|max:15',
            'second_weight' => 'required|string|max:15',
            'amount' => 'required|string|max:5',
            'description' => 'nullable|string|max:25',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $data = $validator->validated();

        $record = Detail::where('id', '=', $data['serial'])->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found',
            ]);
        }

        $record->update([
            'vehicle_number' => $data['vehicle_number'],
            'party' => Str::title($data['party']),
            'first_weight' => $data['first_weight'],
            'second_weight' => $data['second_weight'],
            'amount' => $data['amount'],
            'description' => Str::title($data['description']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Record updated successfully!',
            'record' => $record,
        ]);
    }

    public function get_weight()
    {
        // pm2 start "php artisan weight:listen --device=/dev/ttyUSB0" --name weight-listener

        return response()->json([
            'weight' => Cache::get('current_weight', 0),
            'updated_at' => now()->toDateTimeString(),
        ]);
    }
}
