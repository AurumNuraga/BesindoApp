<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'category_id',
        'supplier_id',
        'brand_id',
        'barcode',
        'tax',
        'unit_per_product',
        'unit_per_koli',
        'sell_price',
        'capital_price',
        'package_id',
        'color',
        'weight',
        'volume',
        'length',
        'width',
        'height',
        'expedition_price',
        'location',
        'status'
    ];

    protected static function booted(): void
    {
        static::created(function (Product $product) {
            $product->stock()->create([
                'stock' => 0,
            ]);
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(BrandCategory::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(PackageCategory::class);
    }

    public function stock(): HasOne
    {
        return $this->hasOne(Stock::class, 'product_id')->withDefault([
            'stock' => 0,
        ]); 
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleTransactionDetail::class);
    }

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseTransactionDetail::class);
    }

    public function purchaseReturnDetails()
    {
        return $this->hasMany(PurchaseReturnDetail::class);
    }

    public function saleReturnDetails()
    {
        return $this->hasMany(SaleReturnDetail::class);
    }
}
