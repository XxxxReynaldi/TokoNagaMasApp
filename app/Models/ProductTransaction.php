<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'status', 'purchaseReceiptPath', 'bank_account_name',
        'bank_name', 'account_number', 'total_price',
    ];

    public function toArray()
    {
        $toArray = parent::toArray();
        $toArray['purchaseReceiptPath'] = $this->purchaseReceiptPath;
        return $toArray;
    }

    public function getPurchaseReceiptPathAttribute()
    {
        return $this->attributes['purchaseReceiptPath'];
        // return url('') . Storage::url($this->attributes['purchaseReceiptPath']);
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
    public function products()
    {
        return $this->belongsToMany(Product::class, 'transaction_details')->withPivot('quantity', 'price');
    }
}
