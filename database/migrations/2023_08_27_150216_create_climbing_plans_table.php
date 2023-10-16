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
        Schema::create('climbing_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mountain_id');
            $table->datetime('schedule_date')->nullable();
            $table->enum('is_map_download', ['SUCCESS', 'FAILED']);
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['ACTIVE', 'INACTIVE']);
            $table->boolean('is_cancel')->default(0);
            $table->enum('gps', ['ACTIVE', 'INACTIVE']);
            $table->enum('status_finished', ['PROCESS', 'FINISH']);
            $table->timestamps();

            $table->foreign('mountain_id')->references('id')->on('mountains');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('climbing_plans');
    }
};
