<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use App\Services\User\DTO\CreateUser;
use App\Services\User\DTO\UpdateUser;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;

    public function findByEmail(string $email): ?User;

    public function all(int $perPage = 15): LengthAwarePaginator;

    public function create(CreateUser $dto): User;

    public function update(User $user, UpdateUser $dto): User;

    public function delete(User $user): bool;

    public function activate(User $user): User;

    public function deactivate(User $user): User;

    public function attachRoles(User $user, array $roleIds): void;

    public function syncRoles(User $user, array $roleIds): void;
}
