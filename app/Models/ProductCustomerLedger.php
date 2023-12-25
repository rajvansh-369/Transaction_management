<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCustomerLedger extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'product_id', 'ledger_id', 'product_qty'];


    public function product()
    {
        return $this->belongsToMany(Product::class);
    }

    public function customer()
    {
        return $this->belongsToMany(Customer::class);
    }


    public function ledger()
    {
        return $this->belongsToMany(Ledger::class);
    }
}
