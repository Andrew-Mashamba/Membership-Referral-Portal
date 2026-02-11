<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user) {
            abort(403, 'Access denied.');
        }
        // Allow both approver and administrator roles (isApprover() is true for both)
        if (! in_array($user->role, ['approver', 'administrator'], true)) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}
