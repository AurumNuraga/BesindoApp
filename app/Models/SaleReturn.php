<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SaleReturn extends Model {
    protected $guarded = ['id'];
    public function details() { return $this->hasMany(SaleReturnDetail::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function warehouse() { return $this->belongsTo(Warehouse::class); }
    public function saleTransaction() { return $this->belongsTo(SaleTransaction::class); }
    public function user() { return $this->belongsTo(User::class); }
}