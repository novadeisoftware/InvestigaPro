<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_steps', function (Blueprint $table) {
            // Añadimos la columna como nullable después de project_id
            $table->foreignId('classroom_step_id')
                  ->nullable() 
                  ->after('project_id')
                  ->constrained()
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('project_steps', function (Blueprint $table) {
            // Eliminamos la clave foránea y la columna
            $table->dropForeign(['classroom_step_id']);
            $table->dropColumn('classroom_step_id');
        });
    }
};