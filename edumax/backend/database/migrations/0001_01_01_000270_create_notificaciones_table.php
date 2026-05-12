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
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('titulo', 150);
            $table->text('mensaje');
            $table->enum('tipo', ['sistema', 'whatsapp', 'sms', 'correo'])->default('sistema');
            $table->boolean('leido')->default(false);
            $table->dateTime('fecha_envio');
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('leido');
            $table->index('fecha_envio');
            $table->index('tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
