<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Requests\Api\V1\LogoutRequest;
use App\Http\Requests\Api\V1\RefreshRequest;
use App\Services\Auth\Contracts\AuthServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Unauthenticated;

#[Group('Аутентификация', 'Эндпоинты для входа, выхода и обновления токенов')]
class AuthController extends Controller
{
    public function __construct(
        private readonly AuthServiceInterface $authService
    ) {}

    /**
     * Вход в систему
     * 
     * Аутентификация пользователя и получение пары токенов (access_token и refresh_token).
     */
    #[Unauthenticated]
    public function login(LoginRequest $request): JsonResponse
    {
        $tokenPair = $this->authService->login($request->toDTO());

        return response()->json([
            'data' => $tokenPair->toArray(),
        ]);
    }

    /**
     * Выход из системы
     * 
     * Отзывает текущие токены доступа и обновления.
     */
    public function logout(LogoutRequest $request): Response
    {
        $accessToken = $request->bearerToken();
        $refreshToken = $request->input('refresh_token');

        if ($accessToken && $refreshToken) {
            $this->authService->logout($accessToken, $refreshToken);
        }

        return response()->noContent();
    }

    /**
     * Обновление токена доступа
     * 
     * Обновляет access_token используя refresh_token.
     */
    #[Unauthenticated]
    public function refresh(RefreshRequest $request): JsonResponse
    {
        $refreshToken = $request->input('refresh_token');

        $tokenPair = $this->authService->refresh($refreshToken);

        return response()->json([
            'data' => $tokenPair->toArray(),
        ]);
    }
}

