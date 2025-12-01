<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showAdminLoginForm()
    {
        return view('auth.admin-login'); 
    }

    /**
     * Handle Admin Authentication.
     */
    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();            
            
            // Authorization Check: Only Admins allowed
            if ($user->role === 'admin') {
                $request->session()->regenerate();
                return to_route('admin.dashboard')->with('success', 'Welcome back, Admin!');
            }

            // Force logout if user is authenticated but not an admin
            $this->terminateSession($request);
            
            return back()->withErrors(['email' => 'Access denied. Administrator privileges required.']);
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->onlyInput('email');
    }
    
    /**
     * Handle User/Customer Authentication.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Check Role
            if ($user->role === 'user') {
                $request->session()->regenerate();
                return redirect()->intended('/')->with('success', 'Login successful!');
            }

            // Force logout if role mismatch
            $this->terminateSession($request);
            
            return back()->withErrors(['email' => 'Please login via the Admin Portal.']);
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        $this->terminateSession($request);
        return redirect('/')->with('success', 'You have logged out successfully.');
    }

    /**
     * Log the admin out.
     */
    public function adminLogout(Request $request)
    {
        $this->terminateSession($request);
        return to_route('admin.login')->with('success', 'Admin session ended.');
    }

    /* |--------------------------------------------------------------------------
    | Private Helper
    |--------------------------------------------------------------------------
    */
    
    /**
     * Terminate the session (DRY Principle).
     */
    private function terminateSession(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}