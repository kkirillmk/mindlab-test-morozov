<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller // todo доделать
{
    public function index(): JsonResponse
    {
        return response()->json(['message' => 'Список ролей']);
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Создание роли']);
    }

    public function show(string $id): JsonResponse
    {
        return response()->json(['message' => 'Просмотр роли']);
    }

    public function destroy(string $id): JsonResponse
    {
        return response()->json(['message' => 'Удаление роли']);
    }
}
