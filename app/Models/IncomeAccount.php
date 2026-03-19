<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeAccount extends Model
{
    protected $guarded = ['id'];

    public function inflows()
    {
        return $this->hasMany(CashInflow::class);
    }
}