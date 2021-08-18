<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    use AuthenticatesUsers {
        logout as performLogout;
    }

    public function register(Request $request)
    {
        try {
            $user = User::create([
                'email' => $request->params['email'],
                'password' => Hash::make($request->params['password']),
                'first_name' => $request->params['first_name'],
                'middle_name' => $request->params['middle_name'],
                'last_name' => $request->params['last_name'],
                'phone' => $request->params['phone'],
                'address' => $request->params['address'],
                'city' => $request->params['city'],
                'zip_code' => $request->params['zip_code'],
                'country' => $request->params['country'],
                'state' => $request->params['province'],
                'birthday' => Carbon::parse($request->params['birthday'])->toDateTimeString()
            ]);

            event(new Registered($user));
        } catch (\Exception $e) {
            Log::critical('Error in user registration. Exception: ' . $e);
        }

        $credentials = ['email' => $request->params['email'], 'password' => $request->params['password']];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $token = $request->session()->token();
            $token = csrf_token();
            $data = ['user' => Auth::user(), 'token' => $token];
            return response()->json($data);
        }
    }

    public function emailcheck(Request $request)
    {
        $result = User::where('email', $request->params['email'])->count();

        if ($result === 0) {
            return response('OK', 200);
        } else {
            return response('Email found', 200);
        }
    }

    public function phonecheck(Request $request)
    {
        $result = User::where('phone', $request->params['phone'])->count();

        if ($result === 0) {
            return response('OK', 200);
        } else {
            return response('Phone found', 200);
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $this->performLogout($request);
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            $token = $request->session()->token();
            $token = csrf_token();
            return response()->json($token);
        } catch (\Exception $e) {
            Log::critical('Error in user logout. Exception: ' . $e);
        }
    }

    public function login(Request $request)
    {
        $credentials = ['email' => $request->params['email'], 'password' => $request->params['password']];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $token = $request->session()->token();
            $token = csrf_token();
            $data = ['user' => Auth::user(), 'token' => $token];
            return response()->json($data);
        } else {
            return response('Error', 200);
        }
    }

    public function user(Request $request)
    {
        $data = Auth::user();
        return response()->json($data);
    }

    public function verification(Request $request)
    {
        return redirect('/not-verified');
    }
}
