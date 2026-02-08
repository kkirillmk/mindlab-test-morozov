<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller  // todo доделать
{
    public function me(Request $request): JsonResource
    {
        return UserResource::make($request->user());
    }

    public function changeOwnPassword(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Смена собственного пароля']);
    }

    public function index(): JsonResponse
    {
        return response()->json(['message' => 'Список пользователей']);
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Создание пользователя']);
    }

    public function show(string $id): JsonResponse
    {
        return response()->json(['message' => 'Просмотр пользователя']);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        return response()->json(['message' => 'Обновление пользователя']);
    }

    public function destroy(string $id): JsonResponse
    {
        return response()->json(['message' => 'Удаление пользователя']);
    }

    public function changePassword(Request $request, string $id): JsonResponse
    {
        return response()->json(['message' => 'Смена пароля пользователя']);
    }

    public function deactivate(string $id): JsonResponse
    {
        return response()->json(['message' => 'Деактивация пользователя']);
    }

    public function activate(string $id): JsonResponse
    {
        return response()->json(['message' => 'Активация пользователя']);
    }
}
