<?php

namespace App\Http\Middleware;

use App\Services\Auth\Exceptions\AuthException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            throw AuthException::invalidToken();
        }

        if (!$user->is_active) {
            throw AuthException::accountDeactivated();
        }

        return $next($request);
    }
}
