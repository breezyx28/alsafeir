<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    protected $fillable = [
        'from_branch_id',
        'to_branch_id',
        'reference',
        'transfer_date',
        'notes',
    ];

    // فرع المصدر
    public function fromBranch()
    {
        return $this->belongsTo(Branch::class, 'from_branch_id');
    }

    // فرع الوجهة
    public function toBranch()
    {
        return $this->belongsTo(Branch::class, 'to_branch_id');
    }

    // الأصناف المحولة ضمن التحويل
    public function items()
    {
        return $this->hasMany(StockTransferItem::class);
    }
}
