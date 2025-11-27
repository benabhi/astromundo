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
        Schema::table('character_ships', function (Blueprint $table) {
            $table->foreignId('solar_system_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('location_type')->nullable(); // 'station', 'space', 'planet'
            $table->unsignedBigInteger('location_id')->nullable();
            $table->integer('coords_x')->default(0);
            $table->integer('coords_y')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('character_ships', function (Blueprint $table) {
            //
        });
    }
};
