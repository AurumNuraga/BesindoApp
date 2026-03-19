<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GeneralJournalDetail extends Model
{
    protected $guarded = ['id'];

    public function creditAccount() {
        return $this->belongsTo(JournalAccount::class, 'credit_account_id');
    }

    public function debitAccount() {
        return $this->belongsTo(JournalAccount::class, 'debit_account_id');
    }
}