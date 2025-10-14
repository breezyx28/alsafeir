<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_date',
        'amount',
        'expense_account_id',
        'cash_account_id',
        'user_id',
        'branch_id',
        'notes',
        'journal_entry_id',
    ];

    public function expenseAccount()
    {
        return $this->belongsTo(Account::class, 'expense_account_id');
    }

    public function cashAccount()
    {
        return $this->belongsTo(Account::class, 'cash_account_id');
    }

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class, 'journal_entry_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    // في حال عندك موديل Branch
    public function branch()
    {
        return $this->belongsTo(\App\Models\Branch::class, 'branch_id');
    }
}
