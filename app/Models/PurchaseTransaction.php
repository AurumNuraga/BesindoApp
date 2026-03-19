<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseTransaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Pastikan format date casting benar (hapus koma yang salah di string)
    protected $casts = [
        'purchase_date' => 'date',
        'due_date' => 'date',
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function details(): HasMany
    {
        // PERBAIKAN: Tambahkan 'purchase_id' sebagai parameter kedua
        return $this->hasMany(PurchaseTransactionDetail::class, 'purchase_id');
    }

    public function purchaseReturn() {
        return $this->hasMany(PurchaseReturn::class);
    }
}