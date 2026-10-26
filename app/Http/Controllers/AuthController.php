<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Controller: AuthController
 * 
 * Handles user authentication, registration for Parents,
 * registration for Hospitals, and role-directed redirection.
 */
class AuthController extends Controller
{
    /**
     * Show the unified login page
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    /**
     * Authenticate user credentials and redirect to appropriate role portal
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $remember = $request->boolean('remember');
        unset($credentials['remember']);

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            return $this->redirectBasedOnRole($user)
                ->with('success', "Welcome back, {$user->name}!");
        }

        throw ValidationException::withMessages([
            'email' => __('The provided credentials do not match our records.'),
        ]);
    }

    /**
     * Show parent registration form
     */
    public function showParentRegister()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.register-parent');
    }

    /**
     * Handle Parent account registration
     */
    public function registerParent(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email', 'unique:hospitals,email'],
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-().\s]+$/'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'parent',
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city' => $validated['city'] ?? 'Metropolis',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('parent.dashboard')
            ->with('success', 'Parent account created successfully! You can now register your child.');
    }

    /**
     * Show Hospital registration form (SRS: Hospital can register with name, address and location)
     */
    public function showHospitalRegister()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.register-hospital');
    }

    /**
     * Handle Hospital registration
     */
    public function registerHospital(Request $request)
    {
        $validated = $request->validate([
            'hospital_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email', 'unique:hospitals,email'],
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-().\s]+$/'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'location' => ['required', 'string', 'max:255'],
            'operating_hours' => ['nullable', 'string', 'max:100'],
        ]);

        // 1. Create hospital user account
        $user = User::create([
            'name' => $validated['hospital_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'hospital',
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city' => $validated['city'],
        ]);

        // 2. Create hospital institution profile
        Hospital::create([
            'user_id' => $user->id,
            'name' => $validated['hospital_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'location' => $validated['location'],
            'contact_person' => $validated['contact_person'],
            'operating_hours' => $validated['operating_hours'] ?? 'Mon-Sat 08:00 AM - 05:00 PM',
            'status' => 'active',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('hospital.dashboard')
            ->with('success', 'Hospital registered successfully! Welcome to the Vaccination Management portal.');
    }

    /**
     * Terminate the authenticated session
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'You have been logged out.');
    }

    /**
     * Internal helper to redirect authenticated user to their role dashboard
     */
    protected function redirectBasedOnRole(User $user)
    {
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'hospital' => redirect()->route('hospital.dashboard'),
            'parent' => redirect()->route('parent.dashboard'),
            default => redirect()->route('home'),
        };
    }
}
