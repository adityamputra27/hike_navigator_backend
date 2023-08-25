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
        Schema::create('peaks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('weight');
            $table->enum('status', ['ACTIVE', 'INACITVE']);
            $table->unsignedBigInteger('user_id');
            $table->longText('description');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peaks');
    }
};
