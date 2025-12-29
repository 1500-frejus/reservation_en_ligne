<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Supprimer la clé étrangère existante
            $table->dropForeign(['trip_id']);
            $table->dropColumn('trip_id');
            
            // Ajouter la nouvelle colonne
            $table->foreignId('route_id')->constrained('routes')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Annuler les changements
            $table->dropForeign(['route_id']);
            $table->dropColumn('route_id');
            $table->foreignId('trip_id')->constrained('trips')->cascadeOnDelete();
        });
    }
};
