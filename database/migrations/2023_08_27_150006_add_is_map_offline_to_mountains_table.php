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
        Schema::table('mountains', function (Blueprint $table) {
            $table->enum('is_map_offline', ['AVAILABLE', 'NOT AVAILABLE'])->after('longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mountains', function (Blueprint $table) {
            //
        });
    }
};
