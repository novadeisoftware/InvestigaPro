<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// ESTA ES LA IMPORTACIÓN QUE TE FALTA:
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentItem extends Model
{
    use SoftDeletes;

    protected $fillable = ['payment_id', 'project_id', 'product_type', 'price', 'quantity'];

    /**
     * Relación con la cabecera del pago.
     * Ahora el tipo de retorno 'BelongsTo' será reconocido correctamente.
     */
    public function payment(): BelongsTo 
    { 
        return $this->belongsTo(Payment::class); 
    }

    /**
     * Relación con el proyecto.
     */
    public function project(): BelongsTo 
    { 
        return $this->belongsTo(Project::class); 
    }
}