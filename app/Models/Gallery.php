<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'mechanic_id', 'galleryPhotoPath', 'description', 'repair_type'
    ];

    public function toArray()
    {
        $toArray = parent::toArray();
        $toArray['galleryPhotoPath'] = $this->galleryPhotoPath;
        return $toArray;
    }

    public function getGalleryPhotoPathAttribute()
    {
        return $this->attributes['galleryPhotoPath'];
        // return url('') . Storage::url($this->attributes['galleryPhotoPath']);
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
        return $this->belongsToMany(Product::class, 'gallery_product');
    }

    public function mechanic()
    {
        return $this->belongsTo(Mechanic::class);
    }
}
