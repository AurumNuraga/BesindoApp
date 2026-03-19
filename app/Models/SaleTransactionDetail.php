<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleTransactionDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(SaleTransaction::class, 'transaction_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function saleTransaction()
    {
        // Berdasarkan kode controller sebelumnya, foreign key Anda bernama 'transaction_id'
        return $this->belongsTo(SaleTransaction::class, 'sale_transaction_id');
    }
}