<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebtInstallment extends Model
{
    use HasFactory;

    protected $fillable = [
        'debt_id',
        'installment_date',
        'amount',
        'paid',
    ];

    // علاقة القسط مع المديونية
    public function debt()
    {
        return $this->belongsTo(Debt::class);
    }
}
