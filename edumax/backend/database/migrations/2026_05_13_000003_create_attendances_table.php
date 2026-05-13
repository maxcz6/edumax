<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('institucion_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('section_id');
            $table->date('date');
            $table->text('observations')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('institucion_id')->references('id')->on('instituciones')->cascadeOnDelete();
            $table->foreign('course_id')->references('id')->on('cursos')->cascadeOnDelete();
            $table->foreign('teacher_id')->references('id')->on('docentes')->cascadeOnDelete();
            $table->foreign('section_id')->references('id')->on('secciones')->cascadeOnDelete();

            $table->index('institucion_id');
            $table->index('course_id');
            $table->index('teacher_id');
            $table->index('section_id');
            $table->index('date');
            $table->unique(['course_id', 'section_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
