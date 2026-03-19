<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BrandCategory extends Model
{
    use HasFactory;

    protected $table = 'brand_categories';

    protected $fillable = [
        'name',
    ];
}
