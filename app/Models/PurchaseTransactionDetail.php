<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseTransactionDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi ke Header Transaksi
    public function purchaseTransaction(): BelongsTo
    {
        // PERBAIKAN: Gunakan 'purchase_id' sesuai database
        return $this->belongsTo(PurchaseTransaction::class, 'purchase_id');
    }

    // Alias agar bisa dipanggil dengan $detail->purchase
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(PurchaseTransaction::class, 'purchase_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}