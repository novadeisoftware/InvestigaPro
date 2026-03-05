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
        Schema::create('classroom_steps', function (Blueprint $table) {
        $table->id();
        
        // Relationship with the classroom (Vínculo con el aula del asesor)
        $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
        
        // Step identifier from the university JSON (Llave técnica del paso)
        $table->string('step_key'); 
        
        // Visual order in the list (Posición en la lista: 1, 2, 3...)
        $table->integer('order'); 

        // Display name chosen by the advisor (Nombre personalizado por el docente)
        $table->string('custom_name')->nullable(); 

        // Extra guidance for the student and MSHO AI (Tips adicionales del docente)
        $table->text('additional_instructions')->nullable(); 

        /**
         * FLOW CONTROL (Control de acceso)
         */
        // Access mode: 'open', 'locked', or 'scheduled'
        $table->string('availability_mode')->default('open');
        
        // Unlock timestamp for 'scheduled' mode (Fecha de apertura automática)
        $table->timestamp('available_at')->nullable();

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
