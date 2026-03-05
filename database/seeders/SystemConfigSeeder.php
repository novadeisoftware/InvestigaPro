<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\SystemDefaultConfig::create([
            'min_palabras'    => 5000,
            'max_palabras'    => 40000,
            'idioma'          => 'es',
            'formato_cita'    => 'APA7',
            'modo_avance'     => 'secuencial',
            'requiere_asesor' => false,
            'requiere_revisor'=> false,
            'bloqueo_etapas'  => true,
            'ia_max_palabras' => 15000,
        ]);
    }
}
