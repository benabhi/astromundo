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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('current_ship_id')->nullable()->after('current_location_id');
            $table->string('current_action')->nullable()->after('current_ship_id');
            $table->timestamp('action_started_at')->nullable()->after('current_action');
            
            $table->foreign('current_ship_id')->references('id')->on('character_ships')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['current_ship_id']);
            $table->dropColumn(['current_ship_id', 'current_action', 'action_started_at']);
        });
    }
};
