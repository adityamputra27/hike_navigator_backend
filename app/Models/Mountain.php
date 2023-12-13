<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mountain extends Model
{
    use HasFactory;

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function mountainImages()
    {
        return $this->hasMany(MountainImage::class);
    }

    public function mountainPeaks()
    {
        return $this->hasMany(MountainPeak::class);
    }

    public function mountainTracks()
    {
        return $this->hasMany(Track::class);
    }

    public function mountainMarks()
    {
        return $this->hasMany(Mark::class);
    }

    public function mountainWaterfalls()
    {
        return $this->hasMany(Waterfall::class);
    }

    public function mountainWatersprings()
    {
        return $this->hasMany(WaterSpring::class);
    }

    public function mountainRivers()
    {
        return $this->hasMany(River::class);
    }

    public function mountainPosts()
    {
        return $this->hasMany(Post::class);
    }

    public function mountainCrossRoads()
    {
        return $this->hasMany(CrossRoad::class);
    }
}
