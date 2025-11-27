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
        Schema::table('solar_systems', function (Blueprint $table) {
            $table->dropColumn(['coords_x', 'coords_y']);
        });
        
        Schema::table('character_ships', function (Blueprint $table) {
            $table->dropColumn(['coords_x', 'coords_y']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solar_systems', function (Blueprint $table) {
            $table->integer('coords_x')->default(0);
            $table->integer('coords_y')->default(0);
        });
        
        Schema::table('character_ships', function (Blueprint $table) {
            $table->integer('coords_x')->default(0);
            $table->integer('coords_y')->default(0);
        });
    }
};
