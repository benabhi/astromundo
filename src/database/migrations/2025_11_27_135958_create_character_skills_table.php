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
        Schema::create('character_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained('characters')->onDelete('cascade');
            $table->foreignId('skill_id')->constrained('skills')->onDelete('cascade');
            $table->integer('level')->default(1);
            $table->integer('xp')->default(0);
            $table->timestamp('injected_at')->useCurrent();
            $table->timestamps();

            $table->unique(['character_id', 'skill_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_skills');
    }
};
