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
       
       Schema::create('universities', function (Blueprint $table) {
           $table->id();
           $table->string('nombre');
           $table->string('siglas', 20);
           $table->string('logo_path')->nullable(); // Guardará la ruta: 'logos/ucv.png'
           $table->json('reglas_json')->nullable(); 
           $table->timestamps();
           $table->softDeletes();
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
