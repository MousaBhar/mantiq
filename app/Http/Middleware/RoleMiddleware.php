<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        string $role
    ): Response {

        // Check if the user is authenticated
        $user = $request->user();

        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        // Check if the user has a role
        if (!$user->role) {
            abort(403, 'User role is not assigned.');
        }

        // Check if the user has the required role
        if ($user->role->slug !== $role) {
            abort(403, 'You are not authorized to access this resource.');
        }

        return $next($request);
    }
}
