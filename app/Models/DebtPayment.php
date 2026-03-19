<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DebtPayment extends Model
{
    protected $guarded = ['id'];

    public function purchaseTransaction()
    {
        return $this->belongsTo(PurchaseTransaction::class);
    }
    
    public function details() {
        return $this->hasMany(DebtPaymentDetail::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cashAccount()
    {
        // Asumsi nama kolom di database adalah 'cash_account_id'
        return $this->belongsTo(CashAccount::class, 'cash_account_id');
    }
}