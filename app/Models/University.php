<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class University extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nombre',
        'siglas',
        'logo_path',
        'reglas_json',
    ];

    /**
     * Casts para manejo automático de JSON
     */
    protected $casts = [
        'reglas_json' => 'array', // Permite usarlo como array de PHP automáticamente
    ];

    /**
     * Relación: Una universidad tiene muchos perfiles de usuarios
     */
    public function profiles(): HasMany
    {
        return $this->hasMany(UniversityProfile::class);
    }

    /**
     * Relación: Una universidad rige muchos proyectos
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Relación: Una universidad tiene una única configuración
     */
    public function config()
    {
        return $this->hasOne(UniversityConfig::class);
    }
}