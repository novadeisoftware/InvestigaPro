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
        Schema::table('classrooms', function (Blueprint $blueprint) {
            // Se agrega después del código de invitación para mantener orden
            $blueprint->string('document_type')
                      ->after('invitation_code')
                      ->default('PROYECTO DE TESIS')
                      ->comment('Tipo de documento: proyecto de tesis , informe tesis, etc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classrooms', function (Blueprint $blueprint) {
            $blueprint->dropColumn('document_type');
        });
    }
};