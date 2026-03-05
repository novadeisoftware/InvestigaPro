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
         Schema::create('projects', function (Blueprint $table) {
             $table->id();
             $table->uuid('uuid')->unique(); // Identificador público seguro (para compartir con el asesor)
             
             // Relaciones principales
             $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Autor de la tesis
             $table->foreignId('university_id')->constrained(); // Universidad que rige el formato (UPAO, UCV)
             $table->foreignId('classroom_id')->nullable()->constrained()->onDelete('set null'); // Aula si es académico
         
             // Contenido y Tipo
             $table->text('title'); // Título de la investigación
             $table->string('document_type'); // Tipo: 'thesis_project' (10 pasos) o 'final_report'
             
             // Estados y Acceso
             $table->string('academic_status')->default('draft'); // draft (borrador), review, approved
             $table->enum('access_type', ['academic', 'independent'])->default('independent');
             
             // Datos Flexibles (JSON)
             $table->json('settings')->nullable();        // Preferencias del editor (UI, temas)
             $table->json('general_data')->nullable();    // Área, línea de investigación, objeto de estudio
             
             // Control de consumo de IA
             $table->integer('ai_word_limit')->default(15000); // Límite total asignado
             $table->integer('ai_words_used')->default(0);    // Contador de palabras consumidas
             
             // Pagos y Vigencia
             $table->string('payment_status')->default('pending'); // pending, paid, expired
             $table->timestamp('expires_at')->nullable();         // Fecha en que vence el acceso
             
             $table->timestamps();
         });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
