<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\University;
use App\Models\UniversityConfig; // Asegúrate de tener este modelo

class UpaoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Creamos la Universidad
        $university = University::create([
            'nombre' => 'Universidad Privada Antenor Orrego',
            'siglas' => 'UPAO',
            'logo_path' => 'universities/upao.png',
            'reglas_json' => [
                'esquema_principal' => 'proyecto_tesis',
                'formatos' => [
                    'proyecto_tesis' => [
                        'nombre' => 'Plan de Tesis / Proyecto de Investigación',
                        'pasos' => [
                            ['orden' => 1, 'titulo' => 'I. Antecedentes', 'instrucciones' => 'Mínimo 5 internacionales y 5 nacionales (últimos 5 años).'],
                            ['orden' => 2, 'titulo' => 'II. Marco Teórico', 'instrucciones' => 'Bases teóricas y científicas sustentadas.'],
                            ['orden' => 3, 'titulo' => 'III. Problema', 'secciones' => ['Realidad Problemática', 'Enunciado del Problema']],
                            ['orden' => 4, 'titulo' => 'IV. Hipótesis', 'instrucciones' => 'Formulación de hipótesis general y específicas.'],
                            ['orden' => 5, 'titulo' => 'V. Objetivos', 'secciones' => ['Objetivo General', 'Objetivos Específicos']],
                            ['orden' => 6, 'titulo' => 'VI. Metodología', 'secciones' => ['Tipo y Diseño', 'Población y Muestra', 'Técnicas e Instrumentos']],
                            ['orden' => 7, 'titulo' => 'VII. Cronograma', 'tipo' => 'planeamiento'],
                            ['orden' => 8, 'titulo' => 'VIII. Presupuesto', 'tipo' => 'planeamiento'],
                            ['orden' => 9, 'titulo' => 'IX. Bibliografía', 'norma' => 'APA 7'],
                            ['orden' => 10, 'titulo' => 'X. Anexos', 'norma' => 'APA 7']
                        ]
                    ],
                    'informe_final' => [
                        'nombre' => 'Informe Final de Tesis (Tesis Completa)',
                        'pasos' => [
                            ['orden' => 1, 'titulo' => 'Introducción'],
                            ['orden' => 2, 'titulo' => 'Marco Teórico'],
                            ['orden' => 3, 'titulo' => 'Metodología'],
                            ['orden' => 4, 'titulo' => 'Resultados'],
                            ['orden' => 5, 'titulo' => 'Discusión'],
                            ['orden' => 6, 'titulo' => 'Conclusiones y Recomendaciones']
                        ]
                    ]
                ]
            ]
        ]);

        // 2. Juntamos la configuración para evitar el error de Integrity Constraint
        UniversityConfig::create([
            'university_id'    => $university->id,
            'min_palabras'     => 5000,
            'max_palabras'     => 40000,
            'idioma'           => 'es',
            'formato_cita'     => 'APA7',
            'modo_avance'      => 'secuencial',
            'requiere_asesor'  => true,  // Evita el error 1048
            'requiere_revisor' => true,
            'bloqueo_etapas'   => true,
            'reglas_extra'     => json_encode([]),
        ]);
    }
}