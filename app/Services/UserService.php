<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Exceptions\UserException;
use App\Services\User\DTO\CreateUser;
use App\Services\User\DTO\UpdateUser;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

readonly class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function getAllUsers(int $perPage = 15): LengthAwarePaginator
    {
        return $this->userRepository->all($perPage);
    }

    public function createUser(CreateUser $dto): User
    {
        $user = $this->userRepository->create($dto);

        if (!empty($dto->roleIds)) {
            $this->userRepository->syncRoles($user, $dto->roleIds);

            return $user->fresh(['roles']);
        }

        return $user;
    }

    public function updateUser(User $user, UpdateUser $dto): User
    {
        $updatedUser = $this->userRepository->update($user, $dto);

        if ($dto->hasRoleIds()) {
            $this->userRepository->syncRoles($updatedUser, $dto->roleIds);

            return $updatedUser->fresh(['roles']);
        }

        return $updatedUser;
    }

    public function deleteUser(User $user): bool
    {
        return $this->userRepository->delete($user);
    }

    public function activateUser(User $user): User
    {
        return $this->userRepository->activate($user);
    }

    public function deactivateUser(User $user): User
    {
        return $this->userRepository->deactivate($user);
    }

    public function changePassword(User $user, string $newPassword): User
    {
        return $this->userRepository->update($user, new UpdateUser(password: $newPassword));
    }

    public function changeOwnPassword(User $user, string $currentPassword, string $newPassword): User
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw UserException::invalidCurrentPassword();
        }

        return $this->changePassword($user, $newPassword);
    }
}
