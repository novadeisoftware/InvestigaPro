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
        Schema::create('subscriptions', function (Blueprint $table) {
             $table->id();
             $table->foreignId('user_id')->constrained()->onDelete('cascade');
             
             // Identificador del plan: 'individual_thesis', 'advisor_pro', 'token_pack'
             $table->string('plan_key'); 
             
             // Estado del acceso
             $table->enum('status', ['pending', 'active', 'expired', 'cancelled'])->default('pending');
             
             // Fechas de validez
             $table->timestamp('starts_at')->nullable();
             $table->timestamp('expires_at')->nullable(); // Alumnos: null (por vida) | Asesores: +30 días
             
             $table->timestamps();
             $table->softDeletes(); // Esto agrega la columna deleted_at
         });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            //
        });
    }
};
