<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showlogin ()
    {
        if(Auth::check()){
            return redirect()->route('dashboard')->with('success', 'Welcome Back');
        }
        return view('Auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|exists:users,username',
            'password' => 'required|string',
        ], [
            'username.exists' => 'No user found with this username!',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $data = $validator->validated();

        $user = User::where('username', '=', $data['username'])->first();
        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No user found!',
            ], 404);
        }

        $credentials = [
            'username' => $data['username'],
            'password' => $data['password'],
        ];

        if(Auth::attempt($credentials, false)){
            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'message' => $user->name . ' logged in successfully!',
                'redirect' => route('dashboard'),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Credentials.'
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('login.form'))->with('success', 'You have been logged out.');
    }

    public function create_backup () {
        if(!Auth::check() && Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Only admin can take backups');
        }

        Artisan::call('backup:db');

        return redirect()->back()->with('success', 'Backup successfull!');
    }
}
