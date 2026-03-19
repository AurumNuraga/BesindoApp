<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DebtPaymentDetail extends Model
{
    protected $guarded = ['id'];

    public function purchaseTransaction() {
        return $this->belongsTo(PurchaseTransaction::class);
    }
}