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
        Schema::create('stargates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solar_system_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->foreignId('destination_system_id')->constrained('solar_systems')->cascadeOnDelete();
            $table->integer('orbit_index')->default(0); // Position in the system
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stargates');
    }
};
