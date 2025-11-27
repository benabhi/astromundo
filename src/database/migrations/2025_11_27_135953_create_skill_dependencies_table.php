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
        Schema::create('skill_dependencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skill_id')->constrained('skills')->onDelete('cascade');
            $table->foreignId('required_skill_id')->constrained('skills')->onDelete('cascade');
            $table->integer('required_level');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_dependencies');
    }
};
