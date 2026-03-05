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
        Schema::create('classroom_user', function (Blueprint $table) {
            $table->id();
            // El Aula
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
            // El Alumno
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            /**
             * status: 'active' (está en el curso), 'dropped' (se retiró)
             * role_in_classroom: Por si quieres que haya delegados o roles dentro del aula
             */
            $table->string('status')->default('active');
            
            $table->softDeletes(); // Como pediste, para mantener historial si lo sacan del aula
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classroom_user');
    }
};
