<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // First check if the user is logged in
        if (!auth()->check()) {
            // If not authenticated at all, redirect to login
            return redirect()->route('login')->with('error', 'Please login to access the admin area.');
        }
        
        // Then check if the authenticated user is an admin
        if (auth()->user()->user_type !== 'admin') {
            // User is logged in but not an admin
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access the admin area.');
        }
        
        // User is authenticated and is an admin, allow the request to proceed
        return $next($request);
    }
}
