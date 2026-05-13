<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attendance_id');
            $table->unsignedBigInteger('student_id');
            $table->enum('status', ['present', 'absent', 'late', 'justified'])->default('present');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('attendance_id')->references('id')->on('attendances')->cascadeOnDelete();
            $table->foreign('student_id')->references('id')->on('estudiantes')->cascadeOnDelete();

            $table->index('attendance_id');
            $table->index('student_id');
            $table->unique(['attendance_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_details');
    }
};
