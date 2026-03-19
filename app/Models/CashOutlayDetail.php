<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashOutlayDetail extends Model
{
    protected $guarded = ['id'];

    public function outlayAccount()
    {
        return $this->belongsTo(OutlayAccount::class);
    }
}