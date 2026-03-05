<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Representa el aula virtual donde un asesor gestiona múltiples proyectos de tesis.
 */
class Classroom extends Model
{
    use SoftDeletes; // Habilita el borrado lógico (deleted_at)

    protected $fillable = [
        'university_id',    // Universidad dueña del aula (ej: UPAO)
        'advisor_id',       // ID del Usuario que actúa como Asesor
        'name',             // Nombre del curso o sección
        'invitation_code',  // Código para que los alumnos se unan
        'status',            // Estado actual del aula (active, inactive, archived)
        'step_strategy'
    ];

    /**
     * Casts de atributos.
     */
    protected $casts = [
        'status' => 'string', // Cambiado a string para manejar estados más complejos
    ];

    /* |--------------------------------------------------------------------------
    | Relaciones Académicas
    |-------------------------------------------------------------------------- */

    /**
     * RELACIÓN: El Asesor (Usuario) que dirige y tiene control sobre el aula.
     */
    public function advisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'advisor_id');
    }

    /**
     * RELACIÓN: Los alumnos vinculados a esta aula mediante invitación.
     * La tabla pivote 'classroom_user' gestiona esta membresía.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'classroom_user')
                    ->withPivot('status') // 'active' o 'dropped' dentro del aula
                    ->withTimestamps();
    }

    /**
     * RELACIÓN: Lista de todos los proyectos de investigación (tesis) en esta aula.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * RELACIÓN: Universidad a la que pertenece administrativamente el aula.
     */
    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }


    /**
     * RELACIÓN: Classrom Steps pasos a seguir segun asesor
     */
    public function classroomSteps()
    {
        return $this->hasMany(ClassroomStep::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'classroom_user')
                    ->using(ClassroomUser::class) // Tu modelo Pivot con SoftDeletes
                    ->withPivot('status', 'joined_at')
                    ->withTimestamps();
    }


}