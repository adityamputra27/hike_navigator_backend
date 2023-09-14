<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;

class MountainImage extends Model
{
    use HasFactory;

    protected $appends = ['url'];
    public function getUrlAttribute()
    {
        return Storage::url('mountain-images/'.$this->attributes['image']);
    }
}
