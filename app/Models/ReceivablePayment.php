<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceivablePayment extends Model
{
    protected $guarded = ['id'];

    public function saleTransaction()
    {
        return $this->belongsTo(SaleTransaction::class);
    }

    public function details() {
        return $this->hasMany(ReceivablePaymentDetail::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}