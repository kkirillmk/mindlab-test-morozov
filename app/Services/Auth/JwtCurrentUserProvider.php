<?php

namespace App\Services\Auth;

use App\Services\Auth\Contracts\CurrentUserProviderInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Throwable;

class JwtCurrentUserProvider implements CurrentUserProviderInterface
{
    public function user(): ?Authenticatable
    {
        try {
            return JWTAuth::parseToken()->authenticate();
        } catch (Throwable) {
            return null;
        }
    }
}
