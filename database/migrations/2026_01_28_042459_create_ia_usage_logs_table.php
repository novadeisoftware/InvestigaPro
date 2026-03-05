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
        Schema::create('ia_usage_logs', function (Blueprint $table) {
            $table->id();
            // Relación con el proyecto
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            
            // Datos de consumo
            $table->integer('palabras_generadas'); // Cuántas palabras devolvió la IA
            $table->string('accion');             // 'generar_parrafo', 'corregir', 'resumir'
            $table->string('paso_titulo');        // Ej: 'VI. Metodología' para saber dónde gastó más
            
            $table->timestamps(); // Esto es vital para filtrar por fecha (Hoy)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ia_usage_logs');
    }
};
