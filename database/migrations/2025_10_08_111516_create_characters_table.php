<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->integer('hp')->default(100);
            $table->integer('mp')->default(50);
            $table->integer('attack')->default(10);
            $table->integer('defense')->default(10);
            $table->integer('speed')->default(10);
            $table->integer('special_attack')->default(10);
            $table->integer('special_defense')->default(10);
            $table->integer('level')->default(1);
            $table->integer('exp')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
