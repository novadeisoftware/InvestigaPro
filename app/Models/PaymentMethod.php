<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'identifier', 'logo', 'is_active', 'config'];
    protected $casts = ['config' => 'array'];

    public function payments() {
        return $this->hasMany(Payment::class);
    }
}