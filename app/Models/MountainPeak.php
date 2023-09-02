<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MountainPeak extends Model
{
    use HasFactory;

    public function peak()
    {
        return $this->belongsTo(Peak::class);
    }
}
