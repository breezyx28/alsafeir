<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TailorAdvance extends Model
{
    use HasFactory;

    protected $fillable = [
        'tailor_id',
        'amount',
        'advance_date',
        'notes',
    ];

    protected $casts = [
        'advance_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function tailor()
    {
        return $this->belongsTo(Tailor::class);
    }
}
