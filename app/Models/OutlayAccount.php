<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OutlayAccount extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'outlay_accounts';
    
    protected $fillable = [
        'name',
    ];
}
