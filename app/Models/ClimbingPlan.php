<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClimbingPlan extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mountain()
    {
        return $this->belongsTo(Mountain::class);
    }

    public function mountainPeak()
    {
        return $this->belongsTo(MountainPeak::class);
    }

    public function track()
    {
        return $this->belongsTo(Track::class);
    }
}
