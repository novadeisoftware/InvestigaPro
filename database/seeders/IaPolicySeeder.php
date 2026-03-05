<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IaPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\IaPolicy::create([
        'scope' => 'system',
        'scope_id' => null, // No aplica a nadie en específico, es global
        'max_palabras_totales' => 15000, // Límite de vida del proyecto
        'max_palabras_diarias' => 1500,  // Límite para que no abusen en un solo día
        'reescritura_permitida' => true,
    ]);
    }
}
