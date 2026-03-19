<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseCategory extends Model
{
    use HasFactory;

    protected $table = 'purchase_categories';

    protected $fillable = [
        'name',
    ];
}
