<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Requests\Api\V1\RefreshRequest;
use App\Services\Auth\Contracts\AuthServiceInterface;
use App\Services\Auth\DTO\Credentials;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthServiceInterface $authService
    ) {}

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = new Credentials(
            email: $request->input('email'),
            password: $request->input('password'),
        );
        
        $tokenPair = $this->authService->login($credentials);

        return response()->json([
            'data' => $tokenPair->toArray(),
        ]);
    }

    public function logout(Request $request): Response
    {
        $accessToken = $request->bearerToken();
        $refreshToken = $request->input('refresh_token');
        
        if ($accessToken && $refreshToken) {
            $this->authService->logout($accessToken, $refreshToken);
        }

        return response()->noContent();
    }

    public function refresh(RefreshRequest $request): JsonResponse
    {
        $refreshToken = $request->input('refresh_token');
        
        $tokenPair = $this->authService->refresh($refreshToken);

        return response()->json([
            'data' => $tokenPair->toArray(),
        ]);
    }
}

