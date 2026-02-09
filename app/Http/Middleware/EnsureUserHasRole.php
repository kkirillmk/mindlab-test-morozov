<?php

namespace App\Http\Middleware;

use App\Services\Auth\Exceptions\AuthException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            throw AuthException::invalidToken();
        }

        $user->loadMissing('roles');

        if (!$user->hasAnyRole($roles)) {
            throw AuthException::forbidden();
        }

        return $next($request);
    }
}
