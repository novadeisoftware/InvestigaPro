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
        // Políticas de IA (Control de costos)
        Schema::create('ia_policies', function (Blueprint $table) {
            $table->id();
            $table->string('scope'); // system, university, project
            $table->bigInteger('scope_id')->nullable();
            $table->integer('max_palabras_totales');
            $table->integer('max_palabras_diarias');
            $table->boolean('reescritura_permitida')->default(true);
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
