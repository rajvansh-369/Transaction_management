<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'bill_no', 'total_amount', 'total_credit', 'total_due', 'labour', 'bardana',];

    // protected $casts = [
    //     'products_data' => 'array',
    // ];

    public function productData()
    {

        // dd($this->product_ids);

    }

    public function products()
    {
        return $this->belongsToMany(Product::class,'product_ledger')->withPivot('product_qty')->withTimestamps();
    }


    public function total_price()
    {
        return $this->belongsToMany(Product::class,'product_ledger')->withPivot('product_qty')->withTimestamps();
        // return $this->belongsToMany(Product::class,'product_ledger')->withPivot('product_qty')->withTimestamps();
    }



    public function customer()
    {
        return $this->belongsToMany(Customer::class);
    }
}
