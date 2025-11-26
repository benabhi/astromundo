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
        Schema::table('characters', function (Blueprint $table) {
            $table->foreignId('station_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('station_module_id')->nullable()->constrained('station_modules')->nullOnDelete();
            $table->integer('happiness')->default(100);
            $table->integer('integrity')->default(100); // Hunger/Forma
            $table->integer('energy')->default(100);
        });
    }

    public function down(): void
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->dropForeign(['station_id']);
            $table->dropForeign(['station_module_id']);
            $table->dropColumn(['station_id', 'station_module_id', 'happiness', 'integrity', 'energy']);
        });
    }
};
