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
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->integer('age');
            $table->date('date_of_birth');
            $table->string('location_id')->default('station-alpha'); // Simplified for now
            $table->timestamps();
        });

        // Skills tables moved to separate migrations
        // 2025_11_27_135939_create_skills_table.php
        // 2025_11_27_135953_create_skill_dependencies_table.php
        // 2025_11_27_135958_create_character_skills_table.php

        Schema::create('ships', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('class'); // Miner, Hauler, Fighter
            $table->timestamps();
        });

        Schema::create('character_ships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->onDelete('cascade');
            $table->foreignId('ship_id')->constrained()->onDelete('cascade');
            $table->string('name')->nullable();
            $table->integer('integrity')->default(100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_ships');
        Schema::dropIfExists('ships');
        // Schema::dropIfExists('character_skills');
        // Schema::dropIfExists('skill_dependencies');
        // Schema::dropIfExists('skills');
        Schema::dropIfExists('characters');
    }
};
