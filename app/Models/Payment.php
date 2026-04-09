<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'advisor_id', 'payment_method_id', 'total_amount', 
        'advisor_commission', 'status', 'transaction_id', 'receipt_path', 'payment_data'
    ];

    protected $casts = ['payment_data' => 'array'];

    public function user() { return $this->belongsTo(User::class); }
    public function advisor() { return $this->belongsTo(User::class, 'advisor_id'); }
    public function method() { return $this->belongsTo(PaymentMethod::class, 'payment_method_id'); }
    public function items() { return $this->hasMany(PaymentItem::class); }
}