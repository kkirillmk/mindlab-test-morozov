<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ChangeOwnPasswordRequest;
use App\Http\Requests\Api\V1\ChangePasswordRequest;
use App\Http\Requests\Api\V1\IndexUserRequest;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;

#[Group('Пользователи', 'Управление профилем пользователя')]
class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    /**
     * Получить информацию о текущем пользователе
     */
    public function me(Request $request): JsonResource
    {
        return UserResource::make($request->user()->loadMissing('roles'));
    }

    /**
     * Изменить свой пароль
     */
    public function changeOwnPassword(ChangeOwnPasswordRequest $request): JsonResponse
    {
        $this->userService->changeOwnPassword(
            $request->user(),
            $request->input('current_password'),
            $request->input('new_password')
        );

        return response()->json([
            'message' => 'Пароль успешно изменён',
        ]);
    }

    /**
     * Список пользователей
     * 
     * Получить список всех пользователей с пагинацией. Требуется роль администратора.
     */
    #[Subgroup('Администрирование', 'Управление пользователями (только для администраторов)')]
    public function index(IndexUserRequest $request): AnonymousResourceCollection
    {
        $validated = $request->validated();
        $users = $this->userService->getAllUsers($validated['per_page']);

        return UserResource::collection($users);
    }

    /**
     * Создать пользователя
     * 
     * Создать нового пользователя. Требуется роль администратора.
     */
    #[Subgroup('Администрирование')]
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->createUser($request->toDTO());

        return response()->json([
            'data' => UserResource::make($user),
        ], 201);
    }

    /**
     * Получить пользователя
     * 
     * Получить информацию о конкретном пользователе. Требуется роль администратора.
     */
    #[Subgroup('Администрирование')]
    public function show(User $user): JsonResponse
    {
        return response()->json([
            'data' => UserResource::make($user->loadMissing('roles')),
        ]);
    }

    /**
     * Обновить пользователя
     * 
     * Обновить информацию о пользователе. Требуется роль администратора.
     */
    #[Subgroup('Администрирование')]
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $updatedUser = $this->userService->updateUser($user, $request->toDTO());

        return response()->json([
            'data' => UserResource::make($updatedUser),
        ]);
    }

    /**
     * Удалить пользователя
     * 
     * Удалить пользователя. Требуется роль администратора.
     */
    #[Subgroup('Администрирование')]
    public function destroy(User $user): JsonResponse
    {
        $this->userService->deleteUser($user);

        return response()->json(null, 204);
    }

    /**
     * Сбросить пароль пользователя
     * 
     * Сбросить пароль пользователя администратором. Требуется роль администратора.
     */
    #[Subgroup('Администрирование')]
    public function resetPassword(ChangePasswordRequest $request, User $user): JsonResponse
    {
        $this->userService->changePassword($user, $request->input('new_password'));

        return response()->json([
            'message' => 'Пароль пользователя успешно сброшен',
        ]);
    }

    /**
     * Деактивировать пользователя
     * 
     * Деактивировать пользователя. Требуется роль администратора.
     */
    #[Subgroup('Администрирование')]
    public function deactivate(User $user): JsonResponse
    {
        $deactivatedUser = $this->userService->deactivateUser($user);

        return response()->json([
            'data' => UserResource::make($deactivatedUser->loadMissing('roles')),
        ]);
    }

    /**
     * Активировать пользователя
     * 
     * Активировать пользователя. Требуется роль администратора.
     */
    #[Subgroup('Администрирование')]
    public function activate(User $user): JsonResponse
    {
        $activatedUser = $this->userService->activateUser($user);

        return response()->json([
            'data' => UserResource::make($activatedUser->loadMissing('roles')),
        ]);
    }
}
