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
        Schema::table('users', function (Blueprint $blueprint) {
            // Agregamos el booleano, por defecto en 'false'
            $blueprint->boolean('is_superadmin')->default(false)->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $blueprint) {
            $blueprint->dropColumn('is_superadmin');
        });
    }
};
