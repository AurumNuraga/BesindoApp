<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleCategory extends Model
{
    use HasFactory;

    protected $table = 'sale_categories';

    protected $fillable = [
        'name',
    ];
}
