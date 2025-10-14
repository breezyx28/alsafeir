<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'tailor_id',
        'order_item_identifier',
        'issue_type',
        'description',
        'severity',
        'status',
        'reported_by',
        'reported_date',
    ];

    protected $casts = [
        'reported_date' => 'date',
        'severity' => 'string',
        'status' => 'string',
    ];

    public function tailor()
    {
        return $this->belongsTo(Tailor::class);
    }
}
