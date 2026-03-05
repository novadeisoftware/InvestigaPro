<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'plan_key',      // 'student_thesis', 'advisor_pro', etc.
        'status',        // 'pending', 'active', 'expired', 'failed'
        'slots_limit',   // Capacidad de alumnos (si es asesor)
        'slots_used',    // Cuántos alumnos ya invitó
        'starts_at',
        'expires_at',
        'payment_details' // JSON de respuesta de Niubiz
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'payment_details' => 'array',
        'slots_limit' => 'integer',
        'slots_used' => 'integer',
    ];

    /* |--------------------------------------------------------------------------
       | Relaciones
       |-------------------------------------------------------------------------- */

    /** El dueño de la suscripción */
    public function user(): BelongsTo 
    { 
        return $this->belongsTo(User::class); 
    }

    /** Historial de pagos Niubiz vinculados a esta suscripción */
    public function payments(): HasMany 
    { 
        return $this->hasMany(Payment::class); 
    }

    /* |--------------------------------------------------------------------------
       | Lógica de Negocio
       |-------------------------------------------------------------------------- */

    /** * Verifica si la suscripción está vigente
     */
    public function isActive(): bool
    {
        if ($this->status !== 'active') return false;
        
        // Si no tiene fecha de expiración, es vitalicia (como el plan de tesis única)
        if (is_null($this->expires_at)) return true;

        return $this->expires_at->isFuture();
    }

    /**
     * Verifica si el asesor aún tiene espacio para más alumnos
     */
    public function hasAvailableSlots(): bool
    {
        return $this->slots_used < $this->slots_limit;
    }

    /**
     * Helper para saber si es un plan de asesor
     */
    public function isAdvisorPlan(): bool
    {
        return in_array($this->plan_key, ['advisor_pro', 'advisor_institution']);
    }
}