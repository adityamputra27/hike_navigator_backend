<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    use HasFactory;

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

    public function waterfalls()
    {
        return $this->hasMany(WaterFall::class);
    }

    public function watersprings()
    {
        return $this->hasMany(WaterSpring::class);
    }

    public function rivers()
    {
        return $this->hasMany(River::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
