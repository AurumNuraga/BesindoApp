<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashAccount extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'cash_accounts';

    protected $fillable = [
        'name',
    ];
}
