<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add guard_name to roles table if missing (use raw SQL to avoid information_schema issues on old MySQL)
        try {
            \DB::statement("ALTER TABLE `roles` ADD COLUMN `guard_name` varchar(191) NOT NULL DEFAULT 'web'");
            \DB::statement("ALTER TABLE `roles` ADD UNIQUE `roles_name_guard_name_unique` (`name`, `guard_name`)");
        } catch (\Throwable $e) {
            // Ignore: column or index may already exist or DB doesn't support the operation
        }

        // Create permissions table
        try {
            Schema::create('permissions', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('guard_name')->default('web');
                $table->timestamps();
                $table->unique(['name', 'guard_name']);
            });
        } catch (\Throwable $e) {
            // ignore if table exists or creation fails on older DB
        }

        // model_has_permissions
        try {
            Schema::create('model_has_permissions', function (Blueprint $table) {
                $table->unsignedBigInteger('permission_id');
                $table->string('model_type');
                $table->unsignedBigInteger('model_id');
                $table->index(['model_id', 'model_type'], 'model_has_permissions_model_id_model_type_index');

                $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
                $table->primary(['permission_id', 'model_id', 'model_type'], 'model_has_permissions_permission_model_type_primary');
            });
        } catch (\Throwable $e) {
            // ignore
        }

        // model_has_roles
        try {
            Schema::create('model_has_roles', function (Blueprint $table) {
                $table->unsignedBigInteger('role_id');
                $table->string('model_type');
                $table->unsignedBigInteger('model_id');
                $table->index(['model_id', 'model_type'], 'model_has_roles_model_id_model_type_index');

                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                $table->primary(['role_id', 'model_id', 'model_type'], 'model_has_roles_role_model_type_primary');
            });
        } catch (\Throwable $e) {
            // ignore
        }

        // role_has_permissions
        try {
            Schema::create('role_has_permissions', function (Blueprint $table) {
                $table->unsignedBigInteger('permission_id');
                $table->unsignedBigInteger('role_id');

                $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                $table->primary(['permission_id', 'role_id'], 'role_has_permissions_permission_id_role_id_primary');
            });
        } catch (\Throwable $e) {
            // ignore
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('role_has_permissions')) {
            Schema::dropIfExists('role_has_permissions');
        }

        if (Schema::hasTable('model_has_roles')) {
            Schema::dropIfExists('model_has_roles');
        }

        if (Schema::hasTable('model_has_permissions')) {
            Schema::dropIfExists('model_has_permissions');
        }

        if (Schema::hasTable('permissions')) {
            Schema::dropIfExists('permissions');
        }

        if (Schema::hasTable('roles') && Schema::hasColumn('roles', 'guard_name')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->dropUnique(['name', 'guard_name']);
                $table->dropColumn('guard_name');
            });
        }
    }
};
