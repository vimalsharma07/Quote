<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
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
        
        

        $admin = Admin::where('email', $request->email)->first();

        if ($admin && $request->password=='admin@123') {
            // Log the admin in manually
            Auth::guard('admin')->login($admin);        
                return redirect()->route('admin.dashboard');
        }

        return redirect()->back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
