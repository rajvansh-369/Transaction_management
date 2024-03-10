<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone', 'address', 'city', 'state', 'country'];


    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
    public function ledger()
    {
        return $this->belongsToMany(Ledger::class);
    }


    public function ledgers()
    {
        return $this->hasMany(Ledger::class);
    }
}
