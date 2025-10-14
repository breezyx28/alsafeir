<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TailorProductionLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "tailor_id",
        "piece_rate_definition_id",
        "quantity",
        "production_date",
        "status",
        "notes",
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        "production_date" => "date",
        "quantity" => "integer",
        "status" => "string",
    ];

    // علاقة سجل الإنتاج مع الترزي
    public function tailor()
    {
        return $this->belongsTo(Tailor::class);
    }

    // علاقة سجل الإنتاج مع تعريف أجر القطعة
    public function pieceRateDefinition()
    {
        return $this->belongsTo(PieceRateDefinition::class);
    }
}