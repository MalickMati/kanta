<?php

namespace App\Http\Controllers;

use App\Models\VehicleRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VehicleRateController extends Controller
{
    public function index()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Only admins are allowed to manage Rate List');
        }

        $rates = VehicleRate::orderBy('vehicle_type', 'asc')->get();

        return view('pages.rate_list', compact('rates'));
    }

    public function getRates()
    {
        $rates = VehicleRate::orderBy('vehicle_type', 'asc')->get();
        return response()->json([
            'success' => true,
            'rates' => $rates,
        ]);
    }

    public function store(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized operation',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'vehicle_type' => 'required|string|max:50|unique:vehicle_rates,vehicle_type',
            'amount' => 'required|numeric|min:0',
        ], [
            'vehicle_type.unique' => 'This vehicle type already exists in the rate list',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $rate = VehicleRate::create([
            'vehicle_type' => Str::title(trim($request->vehicle_type)),
            'amount' => $request->amount,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Vehicle rate added successfully',
            'rate' => $rate,
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized operation',
            ], 403);
        }

        $rate = VehicleRate::find($id);

        if (!$rate) {
            return response()->json([
                'success' => false,
                'message' => 'Rate record not found',
            ], 44);
        }

        $validator = Validator::make($request->all(), [
            'vehicle_type' => 'required|string|max:50|unique:vehicle_rates,vehicle_type,' . $id,
            'amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $rate->update([
            'vehicle_type' => Str::title(trim($request->vehicle_type)),
            'amount' => $request->amount,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Vehicle rate updated successfully',
            'rate' => $rate,
        ]);
    }

    public function destroy($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized operation',
            ], 403);
        }

        $rate = VehicleRate::find($id);

        if (!$rate) {
            return response()->json([
                'success' => false,
                'message' => 'Rate record not found',
            ], 404);
        }

        $rate->delete();

        return response()->json([
            'success' => true,
            'message' => 'Vehicle rate deleted successfully',
        ]);
    }
}
