<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name','price'];

    public function customers()
    {
        return $this->belongsToMany(Customer::class);
    }

    public function ledgers()
    {
        return $this->belongsToMany(Ledger::class,'product_ledger')->withPivot('product_qty')->withTimestamps();
    }

}
