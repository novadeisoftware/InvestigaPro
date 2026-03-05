<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Representa la relación (matrícula) de un alumno en un aula específica.
 * Al heredar de Pivot, Laravel reconoce automáticamente los campos extra.
 */
class ClassroomUser extends Pivot
{
    use SoftDeletes;

    /**
     * IMPORTANTE: Al ser tabla pivote, Laravel a veces no detecta 
     * el incremento automático si no se especifica.
     */
    public $incrementing = true;

    protected $table = 'classroom_user';

    protected $fillable = [
        'classroom_id',
        'user_id',
        'status', // 'active' | 'dropped'
        'joined_at'
    ];

    /**
     * Documentación de lógica de negocio:
     * - active: El alumno tiene acceso total a las herramientas del aula.
     * - dropped: El alumno fue retirado por el asesor, pero mantenemos su historial.
     */
}