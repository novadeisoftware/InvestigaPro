<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectComment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_step_id',
        'user_id',
        'comment',
        'type',
        'is_resolved'
    ];

    /**
     * El paso al que pertenece el comentario
     */
    public function step()
    {
        return $this->belongsTo(ProjectStep::class, 'project_step_id');
    }

    /**
     * El usuario que creó la nota (Asesor)
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}