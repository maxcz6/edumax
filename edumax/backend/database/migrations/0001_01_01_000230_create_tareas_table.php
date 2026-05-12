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
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->string('titulo', 150);
            $table->text('descripcion')->nullable();
            $table->string('archivo', 255)->nullable();
            $table->dateTime('fecha_publicacion');
            $table->dateTime('fecha_entrega');
            $table->enum('estado', ['activo', 'cerrado'])->default('activo');
            $table->timestamps();
            $table->softDeletes();

            $table->index('curso_id');
            $table->index('fecha_entrega');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};
