<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'bill_no', 'total_amount', 'total_credit', 'total_due', 'labour', 'bardana','is_paid', 'invoice_date','sms_sent_type'];

    // protected $casts = [
    //     'products_data' => 'array',
    // ];

    public function productData()
    {

        // dd($this->product_ids);

    }

    public function products()
    {
        return $this->belongsToMany(Product::class,'product_ledger')->withPivot(['product_qty','product_price'])->withTimestamps();
    }


    public function total_price()
    {
        return $this->belongsToMany(Product::class,'product_ledger')->withPivot(['product_qty','product_price'])->withTimestamps();
        // return $this->belongsToMany(Product::class,'product_ledger')->withPivot('product_qty')->withTimestamps();
    }



    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
