<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashInflow extends Model
{
    protected $guarded = ['id'];

    public function cashAccount()
    {
        return $this->belongsTo(CashAccount::class);
    }

    public function details() {
        return $this->hasMany(CashInflowDetail::class);
    }

    public function incomeAccount()
    {
        return $this->belongsTo(IncomeAccount::class);
    }

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}