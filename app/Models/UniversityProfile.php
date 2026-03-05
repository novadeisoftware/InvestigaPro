<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UniversityProfile extends Model
{
    protected $fillable = [
        'user_id',
        'university_id',
        'rol_academico',
        'carrera',
        'facultad',
        'codigo_universitario',
    ];

    /**
     * Relación: El perfil pertenece a un usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: El perfil pertenece a una universidad
     */
    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }
}