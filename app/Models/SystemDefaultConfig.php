<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemDefaultConfig extends Model
{
    protected $fillable = [
        'min_palabras',
        'max_palabras',
        'idioma',
        'formato_cita',
        'modo_avance',
        'requiere_asesor',
        'requiere_revisor',
        'bloqueo_etapas',
        'ia_max_palabras'
    ];

    // Forzamos a que siempre trate con la tabla en singular o plural según tu migración
    protected $table = 'system_default_configs';
}