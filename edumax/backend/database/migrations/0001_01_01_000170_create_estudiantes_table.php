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
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->foreignId('padre_id')->nullable()->constrained('padres')->nullOnDelete();
            $table->string('codigo_estudiante', 50)->unique();
            $table->string('dni', 20)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('genero', ['M', 'F', 'Otro'])->nullable();
            $table->string('direccion', 255)->nullable();
            $table->enum('estado', ['activo', 'retirado', 'egresado'])->default('activo');
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('padre_id');
            $table->index('codigo_estudiante');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};
