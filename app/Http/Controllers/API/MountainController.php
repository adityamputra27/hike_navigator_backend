<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Mountain
};

class MountainController extends Controller
{
    public function index()
    {
        $mountains = Mountain::with([
                                        'province', 'city', 'mountainImages', 'mountainPeaks.mountain', 
                                        'mountainPeaks.peak', 'mountainPeaks.track',
                                        'mountainPeaks.track.marks', 'mountainPeaks.track.waterfalls', 
                                        'mountainPeaks.track.watersprings', 'mountainPeaks.track.rivers', 
                                        'mountainPeaks.track.posts',
                                    ])->orderBy('name', 'ASC')->get();

        return response()->json([
            'message' => 'success',
            'data' => $mountains
        ]);
    }
}
