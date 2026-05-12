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
        Schema::create('entregas_tareas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarea_id')->constrained('tareas')->cascadeOnDelete();
            $table->foreignId('estudiante_id')->constrained('estudiantes')->cascadeOnDelete();
            $table->string('archivo', 255)->nullable();
            $table->text('comentario')->nullable();
            $table->dateTime('fecha_entrega');
            $table->boolean('calificado')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index('tarea_id');
            $table->index('estudiante_id');
            $table->index('calificado');
            $table->unique(['tarea_id', 'estudiante_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entregas_tareas');
    }
};
