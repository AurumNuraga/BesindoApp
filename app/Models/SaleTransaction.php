<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaleTransaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    protected $casts = [
        'transaction_date' => 'date',
        'due_date' => 'date',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(SaleTransactionDetail::class);
    }

    public function warehouse() 
    { 
        return $this->belongsTo(Warehouse::class); 
    }

    public function saleReturn() {
        return $this->hasMany(SaleReturn::class);
    }
}