<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashOutlay extends Model
{
    protected $guarded = ['id'];

    public function cashAccount()
    {
        return $this->belongsTo(CashAccount::class);
    }

    public function details()
    {
        return $this->hasMany(CashOutlayDetail::class);
    }

    public function outlayAccount()
    {
        return $this->belongsTo(OutlayAccount::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi Sales (Mengambil dari tabel users)
    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id');
    }
}