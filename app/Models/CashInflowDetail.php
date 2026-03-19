<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CashInflowDetail extends Model
{
    protected $guarded = ['id'];

    public function incomeAccount() {
        return $this->belongsTo(IncomeAccount::class);
    }
}