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

        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->integer('multiplier')->default(1);
            $table->timestamps();
        });

        Schema::create('skill_dependencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skill_id')->constrained()->onDelete('cascade');
            $table->foreignId('required_skill_id')->constrained('skills')->onDelete('cascade');
            $table->integer('required_level')->default(1);
            $table->timestamps();
        });

        Schema::create('character_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->onDelete('cascade');
            $table->foreignId('skill_id')->constrained()->onDelete('cascade');
            $table->integer('level')->default(1);
            $table->integer('xp')->default(0);
            $table->timestamps();
        });

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
        Schema::dropIfExists('character_skills');
        Schema::dropIfExists('skill_dependencies');
        Schema::dropIfExists('skills');
        Schema::dropIfExists('characters');
    }
};
