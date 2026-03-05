<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IaPolicy extends Model
{
    protected $fillable = [
        'scope',              // system, university, project
        'scope_id',           // ID de la universidad o proyecto (null si es system)
        'max_palabras_totales',
        'max_palabras_diarias',
        'reescritura_permitida'
    ];

    protected $casts = [
        'reescritura_permitida' => 'boolean',
    ];
}