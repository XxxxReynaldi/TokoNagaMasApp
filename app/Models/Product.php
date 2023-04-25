<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'productPhotoPath', 'stock', 'price'
    ];

    public function toArray()
    {
        $toArray = parent::toArray();
        $toArray['productPhotoPath'] = $this->productPhotoPath;
        return $toArray;
    }

    public function getProductPhotoPathAttribute()
    {
        return $this->attributes['productPhotoPath'];
        // return url('') . Storage::url($this->attributes['productPhotoPath']);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }

    // Relation
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
