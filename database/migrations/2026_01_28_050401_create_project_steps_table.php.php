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
        Schema::create('project_steps', function (Blueprint $table) {
            $table->id();
            // Relación con el proyecto padre
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            
            // Orden y Título (Ej: 1 - "I. Realidad Problemática")
            $table->integer('order');
            $table->string('title');
            
            // Contenido principal de la tesis (LongText para soportar mucho contenido)
            $table->longText('content')->nullable();
            
            // Instrucciones, prompts específicos o guías de la universidad en JSON
            $table->json('structured_data')->nullable();
            
            // Estados: pending, in_progress, completed, under_review
            $table->string('status')->default('pending');
            
            // Feedback de la IA MSHO y control de sincronización
            $table->text('ai_feedback')->nullable();
            $table->timestamp('last_ai_sync')->nullable();
            
            $table->timestamps();
            $table->softDeletes(); // Recomendado para evitar pérdidas accidentales
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
