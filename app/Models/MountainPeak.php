<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MountainPeak extends Model
{
    use HasFactory;

    public function mountain()
    {
        return $this->belongsTo(Mountain::class);
    }

    public function peak()
    {
        return $this->belongsTo(Peak::class);
    }

    public function track()
    {
        return $this->hasOne(Track::class);
    }
}
