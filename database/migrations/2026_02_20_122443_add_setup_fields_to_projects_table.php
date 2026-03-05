<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las modificaciones.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $col) {
            // Guardará el JSON con área, objeto, problema, etc.
            if (!Schema::hasColumn('projects', 'general_data')) {
                $col->json('general_data')->nullable()->after('settings');
            }

            // Rastreador de etapas (1: Datos, 2: Título, 3: Finalizado)
            if (!Schema::hasColumn('projects', 'setup_step')) {
                $col->integer('setup_step')->default(1)->after('general_data');
            }

            // Permitimos que el título sea nulo inicialmente mientras se genera
            $col->string('title')->nullable()->change();
        });
    }

    /**
     * Revierte las modificaciones.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $col) {
            $col->dropColumn(['general_data', 'setup_step']);
            $col->string('title')->nullable(false)->change();
        });
    }
};