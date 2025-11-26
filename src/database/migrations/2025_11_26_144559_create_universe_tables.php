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
        Schema::create('solar_systems', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('coords_x');
            $table->integer('coords_y');
            $table->timestamps();
        });

        Schema::create('stars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solar_system_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('type'); // e.g., 'Red Dwarf', 'Yellow Main Sequence'
            $table->text('description')->nullable();
            $table->json('attributes')->nullable(); // For dynamic attributes
            $table->timestamps();
        });

        Schema::create('planets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solar_system_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('type'); // e.g., 'Gas Giant', 'Terrestrial'
            $table->text('description')->nullable();
            $table->integer('orbit_index'); // Position from star
            $table->json('attributes')->nullable();
            $table->timestamps();
        });

        Schema::create('moons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planet_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('type'); // e.g., 'Rocky', 'Icy'
            $table->text('description')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moons');
        Schema::dropIfExists('planets');
        Schema::dropIfExists('stars');
        Schema::dropIfExists('solar_systems');
    }
};
