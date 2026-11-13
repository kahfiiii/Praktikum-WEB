<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsModerator
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isModerator()) {
            abort(403, 'Halaman ini hanya untuk moderator atau admin.');
        }

        return $next($request);
    }
}
