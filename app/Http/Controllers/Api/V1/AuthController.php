<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Requests\Api\V1\LogoutRequest;
use App\Http\Requests\Api\V1\RefreshRequest;
use App\Services\Auth\Contracts\AuthServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthServiceInterface $authService
    ) {}

    public function login(LoginRequest $request): JsonResponse
    {
        $tokenPair = $this->authService->login($request->toDTO());

        return response()->json([
            'data' => $tokenPair->toArray(),
        ]);
    }

    public function logout(LogoutRequest $request): Response
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

