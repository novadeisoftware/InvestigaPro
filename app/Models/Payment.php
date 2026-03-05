<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    /**
     * Atributos asignables masivamente.
     */
    protected $fillable = [
        'user_id',
        'subscription_id',
        'project_id',
        'niubiz_order_id',
        'niubiz_transaction_id',
        'amount',
        'currency',
        'payment_method',
        'status',
    ];

    /**
     * Conversión de tipos automática.
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /* |--------------------------------------------------------------------------
       | Relaciones
       |-------------------------------------------------------------------------- */

    /** El usuario que realizó el pago */
    public function user(): BelongsTo 
    { 
        return $this->belongsTo(User::class); 
    }

    /** La suscripción que se activa con este pago (si aplica) */
    public function subscription(): BelongsTo 
    { 
        return $this->belongsTo(Subscription::class); 
    }

    /** El proyecto (tesis) al que se le recargaron tokens (si aplica) */
    public function project(): BelongsTo 
    { 
        return $this->belongsTo(Project::class); 
    }

    /* |--------------------------------------------------------------------------
       | Scopes (Filtros Rápidos)
       |-------------------------------------------------------------------------- */

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}