<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Вызываем наши сидеры в правильном порядке
        $this->call([
            PermissionSeeder::class,  // Сначала разрешения Spatie и их привязка к ролям
            AdminUserSeeder::class,   // И наконец, создание админа
        ]);

        // Если хочешь создать тестового пользователя, можешь раскомментировать
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
