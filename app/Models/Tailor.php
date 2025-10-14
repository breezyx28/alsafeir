<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tailor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'join_date',
        'status',
        'notes',
        'id_number',
    ];

    protected $casts = [
        'join_date' => 'date',
        'status' => 'string',
    ];

    public function advances()
    {
        return $this->hasMany(TailorAdvance::class);
    }

    public function payments()
    {
        return $this->hasMany(TailorPayment::class);
    }

    public function qualityReports()
    {
        return $this->hasMany(QualityReport::class);
    }
}
