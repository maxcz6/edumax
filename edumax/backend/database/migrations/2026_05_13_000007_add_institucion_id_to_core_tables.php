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
        $tables = ['docentes', 'estudiantes', 'secciones', 'matriculas', 'student_grades'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'institucion_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->foreignId('institucion_id')->after('id')->nullable()->constrained('instituciones')->onDelete('cascade');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['docentes', 'estudiantes', 'secciones', 'matriculas', 'student_grades'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'institucion_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropForeign(['institucion_id']);
                    $table->dropColumn('institucion_id');
                });
            }
        }
    }
};
