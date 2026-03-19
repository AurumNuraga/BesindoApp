<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ReceivablePaymentDetail extends Model
{
    protected $guarded = ['id'];

    public function saleTransaction() {
        return $this->belongsTo(SaleTransaction::class);
    }
}
