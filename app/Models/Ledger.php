<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'total_amount', 'total_credit', 'total_due'];

    public function customers()
    {
        return $this->belongsToMany(Customer::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class,'ledger_product')->withTimestamps();
    }


    public function customProducts()
    {
        return $this->hasMany(Product::class, 'ledger_product');
    }

        // // In your Eloquent model
        // public function products()
        // {
        //     return $this->customProducts()->select('products.id', 'products.name')->distinct();
        // }

}
