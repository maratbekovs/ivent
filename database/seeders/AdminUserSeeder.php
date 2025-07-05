<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Наша модель User
use Illuminate\Support\Facades\Hash; // Для хеширования пароля
use Spatie\Permission\Models\Role; // Используем модель роли Spatie

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'], // Ищем по email
            [
                'name' => 'Администратор Системы',
                'password' => Hash::make('password'), // Пароль по умолчанию 'password'
                'phone' => '+996777123456',
                'position' => 'Главный Администратор',
                // role_id больше не нужен, Spatie управляет связями через pivot-таблицы
            ]
        );

        // Назначаем роль "admin" из Spatie для системы разрешений
        // Важно: роль 'admin' должна быть создана в PermissionSeeder
        $adminUser->assignRole('admin');
    }
}
