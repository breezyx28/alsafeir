<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Branch extends Model
{
    use LogsActivity;

    
    protected $fillable = [
    'name',
    'address',
    'phone',
    'manager_name',
    'status',
];

public function employees()
{
    return $this->hasMany(Employee::class);
}

public function distributions()
{
    return $this->hasMany(Distribution::class);
}

public function stockTransfersFrom()
{
    return $this->hasMany(StockTransfer::class, 'from_branch_id');
}

public function stockTransfersTo()
{
    return $this->hasMany(StockTransfer::class, 'to_branch_id');
}



}
