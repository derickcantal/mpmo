<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure                  $next
     * @param  string[]                  ...$roles   // variadic roles
     */
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        // Grab the currently authenticated user (or null)
        $user = Auth::user();

        // If no user OR user’s role is not in the allowed list → 403
        if (! $user || ! in_array($user->accesstype, $roles, true)) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
