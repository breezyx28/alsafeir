<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TailorPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tailor_id',
        'amount',
        'payment_date',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'payment_method' => 'string',
    ];

    public function tailor()
    {
        return $this->belongsTo(Tailor::class);
    }
}
