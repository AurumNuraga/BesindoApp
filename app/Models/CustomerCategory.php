<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerCategory extends Model
{
    use HasFactory;

    protected $table = 'customer_categories';

    protected $fillable = [
        'name',
    ];
}
