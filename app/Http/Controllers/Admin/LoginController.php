<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {

        $credentials = $request->only('email', 'password');
        $credentials = [
            'email' => trim($request->email),
            'password' => trim($request->password),
        ];
        
        

        $admin = User::where('email', $request->email)->first();

        if ($admin && $request->password=='admin@123') {
            Auth::login($admin);        
                return redirect()->route('admin.dashboard');
        }

        return redirect()->back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout()
    {
        Auth::user()->logout();
        return redirect()->route('admin.login');
    }
}
