<?php

namespace App\Services;

use App\Models\Role;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Services\Role\DTO\CreateRole;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

readonly class RoleService
{
    public function __construct(
        private RoleRepositoryInterface $roleRepository
    ) {}

    public function getAllRoles(): Collection
    {
        return $this->roleRepository->all();
    }

    public function createRole(CreateRole $dto): Role
    {
        if ($dto->slug === null) {
            $dto = new CreateRole(
                name: $dto->name,
                slug: Str::slug($dto->name),
                description: $dto->description
            );
        }

        return $this->roleRepository->create($dto);
    }

    public function deleteRole(Role $role): bool
    {
        return $this->roleRepository->delete($role);
    }
}
