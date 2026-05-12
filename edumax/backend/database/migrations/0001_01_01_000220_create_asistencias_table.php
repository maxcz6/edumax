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
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('estudiantes')->cascadeOnDelete();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->date('fecha');
            $table->enum('estado', ['presente', 'tardanza', 'falta', 'justificado'])->default('presente');
            $table->text('observacion')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('estudiante_id');
            $table->index('curso_id');
            $table->index('fecha');
            $table->index('estado');
            $table->unique(['estudiante_id', 'curso_id', 'fecha']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
