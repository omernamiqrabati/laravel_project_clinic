<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\SupabaseAuthService;

class AuthController extends Controller
{
    protected $supabaseAuth;

    public function __construct(SupabaseAuthService $supabaseAuth)
    {
        $this->supabaseAuth = $supabaseAuth;
    }

    public function showLoginForm()
    {
        // If already logged in, redirect to dashboard
        if (Session::has('authenticated')) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|email',
            'password' => 'required',
        ], [
            'username.email' => 'Please enter a valid email address.',
        ]);

        $email = $request->input('username'); // Using username field as email
        $password = $request->input('password');

        // Authenticate with Supabase
        $authResult = $this->supabaseAuth->signInWithEmail($email, $password);

        if ($authResult['success']) {
            $user = $authResult['user'];
            $profile = $authResult['profile'];

            // Check if user has admin privileges
            if (!$this->supabaseAuth->isAdminUser($profile)) {
                return back()->withErrors([
                    'credentials' => 'Access denied. Only admin users can access the admin panel.',
                ])->withInput();
            }

            // Set session data
            Session::put('authenticated', true);
            Session::put('user_id', $user['id']);
            Session::put('email', $user['email']);
            Session::put('username', $profile['first_name'] . ' ' . $profile['last_name']);
            Session::put('role', $profile['role']);
            Session::put('profile', $profile);
            Session::put('access_token', $authResult['access_token']);
            
            return redirect()->route('admin.dashboard')->with('success', 'Welcome back, ' . $profile['first_name'] . '!');
        }

        return back()->withErrors([
            'credentials' => $authResult['error'] ?? 'Invalid email or password.',
        ])->withInput();
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login')->with('success', 'Logged out successfully!');
    }

    public function showPasswordResetForm()
    {
        return view('auth.forgot-password');
    }

    public function sendPasswordReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');
        $redirectTo = url('/auth/update-password');

        $result = $this->supabaseAuth->resetPassword($email, $redirectTo);

        if ($result['success']) {
            return back()->with('success', 'Password reset email sent! Check your inbox.');
        }

        return back()->withErrors([
            'email' => $result['error'] ?? 'Failed to send password reset email.',
        ])->withInput();
    }

    public function showUpdatePasswordForm()
    {
        return view('auth.update-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $accessToken = Session::get('access_token');
        
        if (!$accessToken) {
            return redirect()->route('login')->withErrors([
                'password' => 'Your session has expired. Please log in again.',
            ]);
        }

        $result = $this->supabaseAuth->updatePassword($accessToken, $request->input('password'));

        if ($result['success']) {
            return redirect()->route('admin.dashboard')->with('success', 'Password updated successfully!');
        }

        return back()->withErrors([
            'password' => $result['error'] ?? 'Failed to update password.',
        ]);
    }
} 