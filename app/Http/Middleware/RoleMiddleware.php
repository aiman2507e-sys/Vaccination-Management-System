<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: RoleMiddleware
 * 
 * Enforces role-based authorization for routes.
 * Supports comma-separated roles, e.g. role:admin,hospital
 */
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this portal.');
        }

        $user = Auth::user();

        // Check if user's role matches any of the allowed roles
        if (!in_array($user->role, $roles)) {
            // Redirect based on the user's actual role to their respective dashboard
            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard')->with('error', 'Unauthorized access redirected to Admin Dashboard.'),
                'hospital' => redirect()->route('hospital.dashboard')->with('error', 'Unauthorized access redirected to Hospital Dashboard.'),
                'parent' => redirect()->route('parent.dashboard')->with('error', 'Unauthorized access redirected to Parent Dashboard.'),
                default => abort(403, 'Unauthorized access to this resource.'),
            };
        }

        return $next($request);
    }
}
