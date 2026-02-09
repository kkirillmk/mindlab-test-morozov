<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreRoleRequest;
use App\Http\Resources\Api\V1\RoleResource;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Knuckles\Scribe\Attributes\Group;

#[Group('Роли', 'Управление ролями пользователей (только для администраторов)')]
class RoleController extends Controller
{
    public function __construct(
        private readonly RoleService $roleService
    ) {}

    /**
     * Список ролей
     * 
     * Получить список всех ролей. Требуется роль администратора.
     */
    public function index(): AnonymousResourceCollection
    {
        $roles = $this->roleService->getAllRoles();

        return RoleResource::collection($roles);
    }

    /**
     * Создать роль
     * 
     * Создать новую роль. Требуется роль администратора.
     */
    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = $this->roleService->createRole($request->toDTO());

        return response()->json([
            'data' => RoleResource::make($role),
        ], 201);
    }

    /**
     * Удалить роль
     * 
     * Удалить роль. Требуется роль администратора.
     */
    public function destroy(Role $role): JsonResponse
    {
        $this->roleService->deleteRole($role);

        return response()->json(null, 204);
    }
}
