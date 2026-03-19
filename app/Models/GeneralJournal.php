<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralJournal extends Model
{
    protected $guarded = ['id'];

    public function creditAccount()
    {
        return $this->belongsTo(JournalAccount::class, 'credit_account_id');
    }

    public function details() {
        return $this->hasMany(GeneralJournalDetail::class);
    }

    public function debitAccount()
    {
        return $this->belongsTo(JournalAccount::class, 'debit_account_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}