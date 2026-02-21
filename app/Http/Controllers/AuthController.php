<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * AuthController - Handles user authentication operations
 * 
 * Manages user registration, login, and logout functionality.
 * Includes form rendering, credential validation, session management,
 * and automatic login for newly registered users.
 */
class AuthController extends Controller
{
    /**
     * Display the login form to unauthenticated users
     * 
     * Checks if user is already authenticated and redirects to dashboard
     * if they are. Otherwise, displays the login form view.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showLogin()
    {
        // Redirect authenticated users to dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Display the registration form to unauthenticated users
     * 
     * Checks if user is already authenticated and redirects to dashboard
     * if they are. Otherwise, displays the registration form view.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showRegister()
    {
        // Redirect authenticated users to dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    /**
     * Handle login form submission
     * 
     * Validates user credentials (email and password), attempts authentication,
     * regenerates session for security, and redirects to intended location
     * or dashboard on success. Returns to login form with error on failure.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validate incoming credentials
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Attempt to authenticate user with provided credentials
        if (Auth::attempt($credentials)) {
            // Regenerate session ID for security (prevents session fixation)
            $request->session()->regenerate();
            
            // Redirect to intended location or dashboard
            return redirect()->intended(route('dashboard'));
        }

        // Authentication failed - return to login with error message
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle user registration form submission
     * 
     * Validates user input (name, email, password with confirmation),
     * creates new user with hashed password, automatically logs them in,
     * and redirects to dashboard with success message.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Validate registration data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',               // Email must be unique
            'password' => 'required|min:6|confirmed',               // Password must be confirmed
        ]);

        // Create new user with hashed password
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),       // Hash password for security
        ]);

        // Automatically log in the newly registered user
        Auth::login($user);

        // Redirect to dashboard with success message
        return redirect()->route('dashboard')->with('success', 'Account created successfully!');
    }

    /**
     * Handle user logout
     * 
     * Logs out the user, invalidates the current session,
     * regenerates CSRF token for security, and redirects
     * to home page with success message.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Log out the authenticated user
        Auth::logout();
        
        // Invalidate the current session
        $request->session()->invalidate();
        
        // Regenerate CSRF token for security
        $request->session()->regenerateToken();

        // Redirect to home with success message
        return redirect('/')->with('success', 'Logged out successfully!');
    }
}
