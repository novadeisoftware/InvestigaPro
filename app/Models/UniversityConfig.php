<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UniversityConfig extends Model
{
    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'university_id',
        'min_palabras',
        'max_palabras',
        'idioma',
        'formato_cita',
        'modo_avance',
        'requiere_asesor',
        'requiere_revisor',
        'bloqueo_etapas',
        'reglas_extra',
    ];

    /**
     * Casts para tipos de datos específicos.
     * Importante para manejar booleanos y el JSON de reglas_extra.
     */
    protected $casts = [
        'requiere_asesor' => 'boolean',
        'requiere_revisor' => 'boolean',
        'bloqueo_etapas'  => 'boolean',
        'reglas_extra'    => 'array', // Permite manipularlo como array de PHP
    ];

    /**
     * Relación: La configuración pertenece a una Universidad.
     */
    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }
}