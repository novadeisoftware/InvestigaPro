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
        Schema::create('university_profiles', function (Blueprint $table) {
            $table->id();
            
            // Relación con el usuario (propietario del perfil)
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade'); // Si se borra el usuario, se borra el perfil

            // Relación con la universidad
            $table->foreignId('university_id')
                  ->constrained()
                  ->onDelete('restrict'); // No dejamos borrar una universidad si tiene perfiles activos

            // Datos Académicos
            $table->string('rol_academico')
                  ->default('alumno'); // alumno, asesor, revisor
            
            $table->string('facultad')->nullable();
            $table->string('carrera')->nullable();
            $table->string('codigo_universitario')->nullable();

            // Auditoría básica
            $table->timestamps();

            // Índice de seguridad: Un usuario solo puede tener un perfil por universidad
            // Esto evita que el mismo user se registre dos veces en la UCV, por ejemplo.
            $table->unique(['user_id', 'university_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('university_profiles');
    }
};