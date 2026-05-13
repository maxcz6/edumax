<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('institucion_id');
            $table->unsignedBigInteger('course_id');
            $table->string('nombre');
            $table->enum('tipo', ['examen', 'tarea', 'proyecto', 'participacion', 'trabajo_grupo', 'otro'])->default('examen');
            $table->integer('peso')->default(1); // peso para promedio
            $table->date('fecha');
            $table->integer('bimestre')->nullable(); // opcional para seguimiento por bimestre
            $table->text('descripcion')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('institucion_id')->references('id')->on('instituciones')->cascadeOnDelete();
            $table->foreign('course_id')->references('id')->on('cursos')->cascadeOnDelete();

            $table->index('institucion_id');
            $table->index('course_id');
            $table->index('tipo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
