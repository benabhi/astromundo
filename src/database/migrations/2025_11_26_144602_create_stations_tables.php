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
        Schema::create('stations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('moon_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('type'); // e.g., 'NPC', 'Player'
            $table->text('description')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });

        Schema::create('station_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('type'); // e.g., 'Hangar', 'Market', 'Cantina'
            $table->text('description')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('station_modules');
        Schema::dropIfExists('stations');
    }
};
