<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'basic_salary',
        'work_days',
        'absent_days',
        'absence_deduction',
        'bonuses_total',
        'penalties_total',
        'net_salary',
        'notes',
    ];

    /**
     * علاقة الراتب بالموظف
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
