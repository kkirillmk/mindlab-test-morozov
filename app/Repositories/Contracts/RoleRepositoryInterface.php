<?php

namespace App\Repositories\Contracts;

use App\Models\Role;
use App\Services\Role\DTO\CreateRole;
use Illuminate\Support\Collection;

interface RoleRepositoryInterface
{
    public function all(): Collection;

    public function findById(int $id): ?Role;

    public function create(CreateRole $dto): Role;

    public function update(Role $role, array $data): Role;

    public function delete(Role $role): bool;
}
