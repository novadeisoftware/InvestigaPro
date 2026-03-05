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
        Schema::create('system_default_configs', function (Blueprint $table) {
            $table->id();
            $table->integer('min_palabras')->default(5000);
            $table->integer('max_palabras')->default(40000);
            $table->string('idioma')->default('es');
            $table->string('formato_cita')->default('APA7');
            $table->string('modo_avance')->default('secuencial');
            $table->boolean('requiere_asesor')->default(false);
            $table->boolean('requiere_revisor')->default(false); // <--- ESTA ES LA QUE FALTA
            $table->boolean('bloqueo_etapas')->default(true);
            $table->integer('ia_max_palabras')->default(15000);
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
