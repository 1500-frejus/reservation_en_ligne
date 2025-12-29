<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('organization_id');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->dropConstrainedForeignId('organization_id');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('organization_id');
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropConstrainedForeignId('organization_id');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropConstrainedForeignId('organization_id');
        });
    }
};
