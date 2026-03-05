<?php

namespace App\Observers;

use App\Models\University;
use App\Models\UniversityConfig;
use App\Models\SystemDefaultConfig;

class UniversityObserver
{
    /**
     * Se ejecuta justo después de que una universidad se guarda en la DB.
     */
    public function created(University $university): void
    {
        // 1. Obtener la configuración maestra
        $base = SystemDefaultConfig::first();

        // 2. Si por alguna razón no hay seeder, creamos valores de emergencia
        // para que la app no explote (Fail-safe)
        if (!$base) {
            $base = new SystemDefaultConfig([
                'min_palabras' => 5000,
                'max_palabras' => 40000,
                'idioma' => 'es',
                'formato_cita' => 'APA7',
                'modo_avance' => 'secuencial',
            ]);
        }

        // 3. Crear la configuración específica para esta universidad
        UniversityConfig::create([
            'university_id'   => $university->id,
            'min_palabras'    => $base->min_palabras,
            'max_palabras'    => $base->max_palabras,
            'idioma'          => $base->idioma,
            'formato_cita'    => $base->formato_cita,
            'modo_avance'     => $base->modo_avance,
            'requiere_asesor' => $base->requiere_asesor,
            'requiere_revisor'=> $base->requiere_revisor,
            'bloqueo_etapas'  => $base->bloqueo_etapas,
            'reglas_extra'    => null, // Empieza limpio
        ]);
    }
}
