<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

protected $fillable = [
    'name', 'email', 'phone', 'national_id', 'hiring_date', 'position', 'salary', 'status', 'branch_id'
];


        public function branch()
            {
                return $this->belongsTo(Branch::class);
            }

        public function user()
            {
                return $this->hasOne(User::class);
            }

                            // app/Models/Employee.php

                public function salaries()
                {
                    return $this->hasMany(Salary::class);
                }

                public function rewards()
                {
                    return $this->hasMany(Reward::class);
                }

                public function deductions()
                {
                    return $this->hasMany(Deduction::class);
                }



}
