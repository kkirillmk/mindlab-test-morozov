<?php

namespace App\Repositories;

use App\Models\Role;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Services\Role\DTO\CreateRole;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class RoleRepository implements RoleRepositoryInterface
{
    private const string CACHE_TAG_ROLES = 'roles';
    private const int CACHE_TTL = 3600;

    private const string CACHE_KEY_ROLES_ALL = 'roles:all';
    private const string CACHE_KEY_ROLE_PREFIX = 'role:';

    public function all(): Collection
    {
        return Cache::tags([self::CACHE_TAG_ROLES])->remember(
            self::CACHE_KEY_ROLES_ALL,
            self::CACHE_TTL,
            fn() => Role::query()->orderBy('id')->get()
        );
    }

    public function findById(int $id): ?Role
    {
        return Cache::tags([self::CACHE_TAG_ROLES])->remember(
            self::CACHE_KEY_ROLE_PREFIX . $id,
            self::CACHE_TTL,
            fn() => Role::find($id)
        );
    }

    public function create(CreateRole $dto): Role
    {
        $data = [
            'name' => $dto->name,
            'slug' => $dto->slug,
            'description' => $dto->description,
        ];

        $role = Role::create($data);

        Cache::tags([self::CACHE_TAG_ROLES])->forget(self::CACHE_KEY_ROLES_ALL);

        Cache::tags(['users'])->flush();

        return $role;
    }

    public function update(Role $role, array $data): Role
    {
        $role->update($data);

        Cache::tags([self::CACHE_TAG_ROLES])->forget(self::CACHE_KEY_ROLES_ALL);
        Cache::tags([self::CACHE_TAG_ROLES])->forget(self::CACHE_KEY_ROLE_PREFIX . $role->id);

        Cache::tags(['users'])->flush();

        return $role->fresh();
    }

    public function delete(Role $role): bool
    {
        $role->users()->detach();

        $deleted = $role->delete();

        if ($deleted) {
            Cache::tags([self::CACHE_TAG_ROLES])->forget(self::CACHE_KEY_ROLES_ALL);
            Cache::tags([self::CACHE_TAG_ROLES])->forget(self::CACHE_KEY_ROLE_PREFIX . $role->id);

            Cache::tags(['users'])->flush();
        }

        return $deleted;
    }
}
