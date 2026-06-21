<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.login'); // Make sure this Blade file exists
    }
    public function loginsubmit(Request $request)
    { 
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('email', 'password');
        if (Auth::guard('web')->attempt($credentials)) { 
            return redirect()->intended('/admin');
        }
        return back()->withErrors([
            'email' => 'Invalid email or password',
        ])->withInput();
    }
 



    public function logout(Request $request )
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}