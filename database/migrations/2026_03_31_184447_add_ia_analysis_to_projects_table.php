<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            // Guardaremos los 10 pasos, el análisis detallado y el resumen ejecutivo aquí
            $table->json('ia_analysis')->nullable()->after('general_data');
            // Para saber si el documento ya fue procesado por la IA
            $table->boolean('has_been_analyzed')->default(false)->after('setup_step');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            //
        });
    }
};
