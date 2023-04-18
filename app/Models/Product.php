<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function galleries()
    {
        return $this->belongsToMany(Gallery::class, 'gallery_product');
    }

    public function cartProducts()
    {
        return $this->hasMany(CartProduct::class);
    }

    public function transactions()
    {
        return $this->belongsToMany(ProductTransaction::class, 'transaction_details')->withPivot('quantity', 'price');
    }
}
