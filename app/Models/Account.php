<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
    protected $fillable = [
        'code',
        'name',
        'type',
        'parent_id',
    ];

    // الحساب الأب (إذا كان هذا حساب فرعي)
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    // الحسابات الفرعية لهذا الحساب
    public function children(): HasMany
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

     public function journalEntryItems()
    {
        return $this->hasMany(\App\Models\JournalEntryItem::class, 'account_id');
    }
}
