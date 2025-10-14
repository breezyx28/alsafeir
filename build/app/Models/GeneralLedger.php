<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralLedger extends Model
{
    protected $table = 'general_ledger';

    protected $fillable = [
        'journal_entry_id',
        'account_name',
        'debit',
        'credit',
        'balance',
        'entry_date',
    ];

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class);
    }
}
