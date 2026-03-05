<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IaUsageLog extends Model
{
    protected $fillable = [
        'project_id',
        'palabras_generadas',
        'accion',
        'paso_titulo'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}