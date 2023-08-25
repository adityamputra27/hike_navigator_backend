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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mountain_peak_id');
            $table->text('title');
            $table->string('latitude');
            $table->string('longitude');
            $table->enum('status', ['ACTIVE', 'INACITVE']);
            $table->unsignedBigInteger('user_id');
            $table->text('description');
            $table->timestamps();

            $table->foreign('mountain_peak_id')->references('id')->on('mountain_peaks');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
