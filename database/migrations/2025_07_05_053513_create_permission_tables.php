<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\PermissionRegistrar; // Убедись, что эта строка есть

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $teams = config('permission.teams');
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        // Получаем экземпляр PermissionRegistrar
        $permissionRegistrar = app(PermissionRegistrar::class);

        // Используем свойства PermissionRegistrar для получения имен pivot-столбцов
        $pivotPermission = $permissionRegistrar->pivotPermission;
        $pivotRole = $permissionRegistrar->pivotRole;

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not loaded. Run [php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"]');
        }

        Schema::create($tableNames['permissions'], function (Blueprint $table) use ($columnNames, $teams) {
            $table->bigIncrements('id'); // permission id
            $table->string('name');       // For example: 'view_posts'
            $table->string('guard_name'); // For example: 'web'
            $table->timestamps();
            if ($teams || config('permission.testing')) {
                $table->unsignedBigInteger($columnNames['team_foreign_key'])->nullable();
                $table->index($columnNames['team_foreign_key']);
            }
            $table->unique(['name', 'guard_name']);
        });

        // ЭТОТ БЛОК ТЕПЕРЬ БУДЕТ СОЗДАВАТЬСЯ ПАКЕТОМ SPATIE
        Schema::create($tableNames['roles'], function (Blueprint $table) use ($columnNames, $teams) {
            $table->bigIncrements('id'); // role id
            $table->string('name');       // For example: 'super-admin'
            $table->string('guard_name'); // For example: 'web'
            $table->timestamps();
            if ($teams || config('permission.testing')) {
                $table->unsignedBigInteger($columnNames['team_foreign_key'])->nullable();
                $table->index($columnNames['team_foreign_key']);
            }
            $table->unique(['name', 'guard_name']);
        });

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames, $pivotPermission) {
            // Используем $pivotPermission как имя столбца
            $table->unsignedBigInteger($pivotPermission);

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');

            $table->foreign($pivotPermission) // Используем $pivotPermission
                ->references('id') // permission id
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->unique([$pivotPermission, $columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_permission_model_type_unique');
        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames, $teams, $pivotRole) {
            // Используем $pivotRole как имя столбца
            $table->unsignedBigInteger($pivotRole);

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');

            $table->foreign($pivotRole) // Используем $pivotRole
                ->references('id') // role id
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            if ($teams) {
                $table->unsignedBigInteger($columnNames['team_foreign_key']);
                $table->index($columnNames['team_foreign_key']);
                $table->unique([$pivotRole, $columnNames['model_morph_key'], 'model_type', $columnNames['team_foreign_key']], 'model_has_roles_role_model_type_team_unique');
            } else {
                $table->unique([$pivotRole, $columnNames['model_morph_key'], 'model_type'], 'model_has_roles_role_model_type_unique');
            }
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames, $pivotPermission, $pivotRole) {
            // Используем $pivotPermission и $pivotRole как имена столбцов
            $table->unsignedBigInteger($pivotPermission);
            $table->unsignedBigInteger($pivotRole);

            $table->foreign($pivotPermission) // Используем $pivotPermission
                ->references('id') // permission id
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign($pivotRole) // Используем $pivotRole
                ->references('id') // role id
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->unique([$pivotPermission, $pivotRole], 'role_has_permissions_permission_id_role_id_unique');

            app('cache')
                ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
                ->forget(config('permission.cache.key'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableNames = config('permission.table_names');

        Schema::dropIfExists($tableNames['role_has_permissions']);
        Schema::dropIfExists($tableNames['model_has_roles']);
        Schema::dropIfExists($tableNames['model_has_permissions']);
        Schema::dropIfExists($tableNames['roles']);
        Schema::dropIfExists($tableNames['permissions']);
    }
};
