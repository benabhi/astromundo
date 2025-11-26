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
        Schema::table('ships', function (Blueprint $table) {
            $table->integer('energy_capacity')->default(100);
            $table->integer('current_energy')->default(100);
        });
    }

    public function down(): void
    {
        Schema::table('ships', function (Blueprint $table) {
            $table->dropColumn(['energy_capacity', 'current_energy']);
        });
    }
};
