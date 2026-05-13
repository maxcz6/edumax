<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institucion_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('institucion_id');
            $table->unsignedBigInteger('user_id');
            $table->string('rol_en_institucion')->nullable();
            $table->timestamps();

            $table->foreign('institucion_id')->references('id')->on('instituciones')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['institucion_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institucion_user');
    }
};
