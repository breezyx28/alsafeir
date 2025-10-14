<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = ['employee_id', 'start_time', 'end_time'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}

