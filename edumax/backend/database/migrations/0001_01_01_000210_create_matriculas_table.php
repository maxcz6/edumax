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
        Schema::create('matriculas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('estudiantes')->cascadeOnDelete();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->foreignId('seccion_id')->constrained('secciones')->cascadeOnDelete();
            $table->year('anio_escolar');
            $table->date('fecha_matricula');
            $table->enum('estado', ['activo', 'retirado', 'finalizado'])->default('activo');
            $table->timestamps();
            $table->softDeletes();

            $table->index('estudiante_id');
            $table->index('curso_id');
            $table->index('seccion_id');
            $table->index('anio_escolar');
            $table->index('estado');
            $table->unique(['estudiante_id', 'curso_id', 'anio_escolar']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matriculas');
    }
};
