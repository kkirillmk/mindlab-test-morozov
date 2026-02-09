<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Администратор',
                'slug' => 'admin',
                'description' => 'Полный доступ к системе',
            ],
            [
                'name' => 'Менеджер',
                'slug' => 'manager',
                'description' => 'Управление пользователями',
            ],
            [
                'name' => 'Пользователь',
                'slug' => 'user',
                'description' => 'Базовый доступ',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}
