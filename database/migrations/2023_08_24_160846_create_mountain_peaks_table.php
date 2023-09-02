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
        Schema::create('mountain_peaks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mountain_id');
            $table->unsignedBigInteger('peak_id');
            $table->string('latitude');
            $table->string('longitude');
            $table->enum('status', ['ACTIVE', 'INACTIVE']);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('mountain_id')->references('id')->on('mountains');
            $table->foreign('peak_id')->references('id')->on('peaks');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mountain_peaks');
    }
};
