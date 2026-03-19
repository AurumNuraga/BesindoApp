<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'name',
        'address',
        'telephone',
        'hp',
        'category_id',
        'city',
        'province',
        'postal_code',
        'country',
        'fax',
        'hp2',
        'tax_name',
        'information',
        'npw',
        'nppkp',
        'ekspedisi',
        'account_number',
        'status'
    ];
}
