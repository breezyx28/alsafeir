<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'type',      // customer أو supplier
        'status',    // debit أو credit
        'total_amount',
        'notes',
    ];

    // علاقة المديونية مع الأقساط
    public function installments()
    {
        return $this->hasMany(DebtInstallment::class);
    }
}
