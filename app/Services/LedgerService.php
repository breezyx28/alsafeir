<?php

namespace App\Services;

use App\Models\GeneralLedger;
use App\Models\JournalEntry;

class LedgerService
{
    /**
     * ترحيل قيد يومية إلى دفتر الأستاذ
     */
    public function postToLedger(JournalEntry $entry)
    {
        // الرصيد (debit - credit)
        $balance = $entry->debit - $entry->credit;

        GeneralLedger::create([
            'journal_entry_id' => $entry->id,
            'account_name'     => $entry->account_name,
            'debit'            => $entry->debit,
            'credit'           => $entry->credit,
            'balance'          => $balance,
            'entry_date'       => $entry->entry_date,
        ]);
    }
}
