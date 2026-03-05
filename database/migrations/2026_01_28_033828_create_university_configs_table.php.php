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
        // Configuración específica por Universidad
      Schema::create('university_configs', function (Blueprint $table) {
          $table->id();
          $table->foreignId('university_id')->constrained()->onDelete('cascade');
          
          $table->integer('min_palabras');
          $table->integer('max_palabras');
          $table->string('idioma');
          $table->string('formato_cita');
          $table->string('modo_avance');
          
          $table->boolean('requiere_asesor')->default(false);
          $table->boolean('requiere_revisor')->default(false); // <--- AGREGAR ESTA
          $table->boolean('bloqueo_etapas')->default(true);   // <--- AGREGAR ESTA (si falta)
          
          $table->json('reglas_extra')->nullable();
          $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
