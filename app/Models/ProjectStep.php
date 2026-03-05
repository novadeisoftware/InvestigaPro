<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectStep extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'classroom_step_id',
        'order',
        'title',
        'content',
        'structured_data',
        'status',
        'ai_feedback',
        'last_ai_sync',
    ];

    /**
     * Casts para manejo automático de tipos de datos.
     */
    protected $casts = [
        'structured_data' => 'array',
        'last_ai_sync'    => 'datetime',
    ];

    /**
     * Relación: Un paso pertenece a un solo proyecto.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Helper para verificar si el paso ya tiene contenido redactado.
     */
    public function hasContent(): bool
    {
        return !empty($this->content);
    }

    public function selectStep($stepId)
    {
        if ($this->currentStepId) {
            $this->saveProgress();
        }
    
        $step = ProjectStep::find($stepId);
        $this->currentStepId = $step->id;
        $this->content = $step->content ?? '';
        
        // AQUÍ: Si el paso ya tiene fecha de actualización, la mostramos
        $this->lastSaved = $step->updated_at ? $step->updated_at->format('h:i A') : null;
    }

    public function classroomStep()
    {
        return $this->belongsTo(ClassroomStep::class, 'classroom_step_id');
    }
}