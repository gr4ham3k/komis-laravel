<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()
                ->route('login')
                ->with('error', 'Please log in to access this page.');
        }

        if (!$user->is_admin) {
            abort(403, 'You do not have access to this page.');
        }

        return $next($request);
    }
}
