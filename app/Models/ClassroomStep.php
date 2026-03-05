<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassroomStep extends Model
{
    protected $fillable = [
        'classroom_id',
        'step_key',             // La llave del JSON (ej: 'introduccion')
        'order',                // El número del paso (1, 2, 3...)
        'custom_name',          // Nombre que el docente elige
        'additional_instructions', // Tips extras del asesor
        'availability_mode',    // 'open', 'locked', 'scheduled'
        'available_at'          // Fecha para el modo programado
    ];

    protected $casts = [
        'available_at' => 'datetime',
    ];

    /**
     * RELACIÓN: El paso pertenece a un aula.
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }
}