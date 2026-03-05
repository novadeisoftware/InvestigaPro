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
        Schema::create('project_comments', function (Blueprint $table) {
            $table->id();
            // Relación con el paso específico (Capítulo) del proyecto
            $table->foreignId('project_step_id')->constrained()->onDelete('cascade');
            // Quién escribe (Asesor)
            $table->foreignId('user_id')->constrained(); 
            // El contenido de la corrección
            $table->text('comment');
            // Metadata adicional para el estilo MSHO
            $table->enum('type', ['observation', 'correction', 'critical'])->default('observation');
            $table->boolean('is_resolved')->default(false); // Para que el alumno marque como "Corregido"
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_comments');
    }
};
