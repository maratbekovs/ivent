<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Сначала создаем все разрешения
        $permissions = [
            'view_dashboard',

            // Инвентаризация техники
            'view_assets', 'create_assets', 'edit_assets', 'delete_assets', 'manage_asset_qrcodes',
            'manage_asset_categories',
            'manage_asset_statuses',

            // Ответственные лица (пользователи)
            'view_users', 'create_users', 'edit_users', 'delete_users', 'assign_roles_to_users',
            'manage_users',

            // Кабинеты и локации
            'view_locations', 'create_locations', 'edit_locations', 'delete_locations', 'manage_room_qrcodes',
            'manage_locations',

            // Склад и перемещения
            'view_stock_movements', 'create_stock_movements', 'edit_stock_movements', 'delete_stock_movements',
            'manage_stock_movements',

            // Документооборот
            'view_documents', 'create_documents', 'edit_documents', 'delete_documents', 'sign_documents',

            // Заявки и обслуживание
            'view_requests', 'create_requests', 'edit_requests', 'delete_requests', 'assign_requests', 'update_request_status',

            // Отчетность
            'view_reports',

            // Сканер инвентаря
            'scan_inventory', // НОВОЕ РАЗРЕШЕНИЕ

            // Настройки системы (доступ только админу)
            'manage_roles', 'manage_permissions', 'manage_settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // 2. Очищаем кэш разрешений Spatie ПОСЛЕ создания всех разрешений
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 3. Теперь назначаем разрешения ролям
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo(Permission::all());

        $itDepartmentRole = Role::firstOrCreate(['name' => 'it_department', 'guard_name' => 'web']);
        $itDepartmentRole->givePermissionTo([
            'view_dashboard',
            'view_assets', 'create_assets', 'edit_assets', 'delete_assets', 'manage_asset_qrcodes',
            'manage_asset_categories', 'manage_asset_statuses',
            'view_users', 'edit_users', 'manage_users',
            'view_locations', 'create_locations', 'edit_locations', 'delete_locations', 'manage_room_qrcodes', 'manage_locations',
            'view_stock_movements', 'create_stock_movements', 'edit_stock_movements', 'delete_stock_movements', 'manage_stock_movements',
            'view_documents', 'create_documents', 'edit_documents', 'sign_documents',
            'view_requests', 'create_requests', 'edit_requests', 'delete_requests', 'assign_requests', 'update_request_status',
            'view_reports',
            'scan_inventory', // IT-отдел тоже может сканировать
        ]);

        $responsiblePersonRole = Role::firstOrCreate(['name' => 'responsible_person', 'guard_name' => 'web']);
        $responsiblePersonRole->givePermissionTo([
            'view_dashboard',
            'view_assets',
            'create_requests',
            'scan_inventory', // Ответственное лицо может сканировать
        ]);

        $auditorRole = Role::firstOrCreate(['name' => 'auditor', 'guard_name' => 'web']);
        $auditorRole->givePermissionTo([
            'view_dashboard',
            'view_assets',
            'view_users',
            'view_locations',
            'view_stock_movements',
            'view_documents',
            'view_requests',
            'view_reports',
            'scan_inventory', // Аудитор может сканировать
        ]);

        $directorRole = Role::firstOrCreate(['name' => 'director', 'guard_name' => 'web']);
        $directorRole->givePermissionTo([
            'view_dashboard',
            'view_assets',
            'view_users',
            'view_locations',
            'view_stock_movements',
            'view_documents',
            'view_requests',
            'view_reports',
            'scan_inventory', // Директор может сканировать
        ]);

        // ВРЕМЕННАЯ ОТЛАДКА: Проверка разрешений для админа после сидинга
        $adminUser = User::where('email', 'admin@example.com')->first();
        if ($adminUser) {
            $this->command->info('--- Отладка разрешений админа после PermissionSeeder ---');
            $this->command->info('Роли админа: ' . implode(', ', $adminUser->getRoleNames()->toArray()));
            $this->command->info('Разрешения админа (прямые и через роли): ' . implode(', ', $adminUser->getAllPermissions()->pluck('name')->toArray()));
            $this->command->info('Есть ли scan_inventory: ' . ($adminUser->hasPermissionTo('scan_inventory') ? 'ДА' : 'НЕТ'));
            $this->command->info('----------------------------------------------------');
        }
    }
}
