<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Mechanic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'status', 'mechanicPhotoPath'
    ];

    public function toArray()
    {
        $toArray = parent::toArray();
        $toArray['mechanicPhotoPath'] = $this->mechanicPhotoPath;
        return $toArray;
    }

    public function getMechanicPhotoPathAttribute()
    {
        return $this->attributes['mechanicPhotoPath'];
        // return url('') . Storage::url($this->attributes['mechanicPhotoPath']);
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
    public function mechanicTransaction()
    {
        return $this->hasOne(MechanicTransaction::class);
    }

    public function mechanicGallery()
    {
        return $this->hasOne(Gallery::class);
    }
}
