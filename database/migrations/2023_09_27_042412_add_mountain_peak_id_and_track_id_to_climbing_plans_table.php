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
        Schema::table('climbing_plans', function (Blueprint $table) {
            $table->unsignedBigInteger('mountain_peak_id')->nullable()->after('mountain_id');
            $table->unsignedBigInteger('track_id')->nullable()->after('mountain_peak_id');

            $table->foreign('mountain_peak_id')->references('id')->on('mountain_peaks');
            $table->foreign('track_id')->references('id')->on('tracks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('climbing_plans', function (Blueprint $table) {
            //
        });
    }
};
