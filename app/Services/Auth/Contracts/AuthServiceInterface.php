<?php

namespace App\Services\Auth\Contracts;

use App\Services\Auth\DTO\Credentials;
use App\Services\Auth\DTO\TokenPair;
use App\Services\Auth\Exceptions\AuthException;

interface AuthServiceInterface
{
    /**
     * @throws AuthException
     */
    public function login(Credentials $credentials): TokenPair;
    
    /**
     * @throws AuthException
     */
    public function refresh(string $refreshToken): TokenPair;
    
    /**
     * @throws AuthException
     */
    public function logout(string $accessToken, string $refreshToken): void;
}
