<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role; // Используем модель роли Spatie

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Очищаем кэш разрешений
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Определяем разрешения
        $permissions = [
            // Общие разрешения
            'view_dashboard',

            // Инвентаризация техники
            'view_assets',
            'create_assets',
            'edit_assets',
            'delete_assets',
            'manage_asset_qrcodes', // Управление QR-кодами активов

            // Категории и статусы
            'manage_asset_categories',
            'manage_asset_statuses',

            // Ответственные лица (пользователи)
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'assign_roles_to_users', // Разрешение на назначение ролей

            // Кабинеты и локации
            'view_locations',
            'create_locations',
            'edit_locations',
            'delete_locations',
            'manage_room_qrcodes', // Управление QR-кодами кабинетов

            // Склад и перемещения
            'view_stock_movements',
            'create_stock_movements',
            'edit_stock_movements',
            'delete_stock_movements',

            // Документооборот
            'view_documents',
            'create_documents',
            'edit_documents',
            'delete_documents',
            'sign_documents',

            // Заявки и обслуживание
            'view_requests',
            'create_requests',
            'edit_requests',
            'delete_requests',
            'assign_requests',
            'update_request_status',

            // Отчетность
            'view_reports',

            // Настройки системы (доступ только админу)
            'manage_roles',
            'manage_permissions',
            'manage_settings',
        ];

        // Создаем разрешения
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Создаем роли Spatie и назначаем разрешения
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo(Permission::all());

        $itDepartmentRole = Role::firstOrCreate(['name' => 'it_department', 'guard_name' => 'web']);
        $itDepartmentRole->givePermissionTo([
            'view_dashboard',
            'view_assets', 'create_assets', 'edit_assets', 'delete_assets', 'manage_asset_qrcodes',
            'manage_asset_categories', 'manage_asset_statuses',
            'view_users', 'edit_users',
            'view_locations', 'create_locations', 'edit_locations', 'delete_locations', 'manage_room_qrcodes',
            'view_stock_movements', 'create_stock_movements', 'edit_stock_movements', 'delete_stock_movements',
            'view_documents', 'create_documents', 'edit_documents', 'sign_documents',
            'view_requests', 'create_requests', 'edit_requests', 'delete_requests', 'assign_requests', 'update_request_status',
            'view_reports',
        ]);

        $responsiblePersonRole = Role::firstOrCreate(['name' => 'responsible_person', 'guard_name' => 'web']);
        $responsiblePersonRole->givePermissionTo([
            'view_dashboard',
            'view_assets',
            'create_requests',
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
        ]);
    }
}
