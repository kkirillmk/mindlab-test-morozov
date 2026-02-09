<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\User\DTO\CreateUser;
use App\Services\User\DTO\UpdateUser;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    private const string CACHE_TAG_USERS = 'users';
    private const string CACHE_TAG_USER_PREFIX = 'user:';
    private const string CACHE_TAG_USERS_LIST = 'users:list';
    private const int CACHE_TTL = 3600;
    
    private const string CACHE_KEY_USER_PREFIX = 'user:';
    private const string CACHE_KEY_USERS_LIST_PREFIX = 'users:list:';

    private function normalizeEmail(string $email): string
    {
        return strtolower(trim($email));
    }

    public function findById(int $id): ?User
    {
        return Cache::tags([self::CACHE_TAG_USERS, self::CACHE_TAG_USER_PREFIX . $id])->remember(
            self::CACHE_KEY_USER_PREFIX . $id,
            self::CACHE_TTL,
            fn() => User::with('roles')->find($id)
        );
    }

    public function findByEmail(string $email): ?User
    {
        $normalizedEmail = $this->normalizeEmail($email);
        
        return User::with('roles')->where('email', $normalizedEmail)->first();
    }

    public function all(int $perPage = 15): LengthAwarePaginator
    {
        $page = request()->integer('page', 1);
        $cacheKey = self::CACHE_KEY_USERS_LIST_PREFIX . "perPage:$perPage:page:$page";
        
        return Cache::tags([self::CACHE_TAG_USERS_LIST])->remember(
            $cacheKey,
            self::CACHE_TTL,
            fn() => User::with('roles')->paginate($perPage)
        );
    }

    public function create(CreateUser $dto): User
    {
        $user = User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
            'is_active' => $dto->isActive,
        ]);
        
        Cache::tags([self::CACHE_TAG_USERS_LIST])->flush();
        
        return $user->fresh(['roles']);
    }

    public function update(User $user, UpdateUser $dto): User
    {
        $updateData = $dto->toArray();
        
        if ($dto->password !== null) {
            $updateData['password'] = Hash::make($dto->password);
        }
        
        $user->update($updateData);
        
        Cache::tags([self::CACHE_TAG_USERS, self::CACHE_TAG_USER_PREFIX . $user->id])->flush();
        Cache::tags([self::CACHE_TAG_USERS_LIST])->flush();
        
        return $user->fresh(['roles']);
    }

    public function delete(User $user): bool
    {
        $userId = $user->id;
        
        $deleted = $user->delete();
        
        if ($deleted) {
            Cache::tags([self::CACHE_TAG_USERS, self::CACHE_TAG_USER_PREFIX . $userId])->flush();
            Cache::tags([self::CACHE_TAG_USERS_LIST])->flush();
        }
        
        return $deleted;
    }

    public function activate(User $user): User
    {
        return $this->update($user, new UpdateUser(isActive: true));
    }

    public function deactivate(User $user): User
    {
        return $this->update($user, new UpdateUser(isActive: false));
    }

    public function attachRoles(User $user, array $roleIds): void
    {
        $user->roles()->attach($roleIds);
        
        Cache::tags([self::CACHE_TAG_USERS, self::CACHE_TAG_USER_PREFIX . $user->id])->flush();
        Cache::tags([self::CACHE_TAG_USERS_LIST])->flush();
    }

    public function syncRoles(User $user, array $roleIds): void
    {
        $user->roles()->sync($roleIds);
        
        Cache::tags([self::CACHE_TAG_USERS, self::CACHE_TAG_USER_PREFIX . $user->id])->flush();
        Cache::tags([self::CACHE_TAG_USERS_LIST])->flush();
    }
}
