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
       Schema::create('classrooms', function (Blueprint $table) {
           $table->id();
           $table->foreignId('university_id')->constrained()->onDelete('cascade');
           $table->foreignId('advisor_id')->constrained('users')->onDelete('cascade');
       
           $table->string('name');
           $table->string('invitation_code')->unique();
           
           /** * Status sugeridos: 
            * 'active'   -> Alumnos pueden unirse y editar proyectos.
            * 'archived' -> Solo lectura (histórico).
            * 'hidden'   -> No aparece en búsquedas pero sigue vigente.
            */
           $table->string('status')->default('active'); 
       
           $table->softDeletes(); // Requerido por tu instrucción previa
           $table->timestamps();
       });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classroom');
    }
};
