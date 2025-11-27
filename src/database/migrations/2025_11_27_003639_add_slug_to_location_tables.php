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
        Schema::table('solar_systems', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
        });
        Schema::table('planets', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
        });
        Schema::table('moons', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
        });
        Schema::table('stations', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solar_systems', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
        Schema::table('planets', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
        Schema::table('moons', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
        Schema::table('stations', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
