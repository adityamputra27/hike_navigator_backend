<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Mountain
};
use Storage;

class MountainController extends Controller
{
    public function index()
    {
        $mountains = Mountain::with([
                                        'province', 'city', 'mountainImages', 'mountainPeaks.mountain', 
                                        'mountainPeaks.peak', 'mountainPeaks.tracks',
                                        'mountainPeaks.tracks.marks', 'mountainPeaks.tracks.waterfalls', 
                                        'mountainPeaks.tracks.watersprings', 'mountainPeaks.tracks.rivers', 
                                        'mountainPeaks.tracks.posts',
                                    ])->orderBy('name', 'ASC')->get();

        return response()->json([
            'message' => 'success',
            'data' => $mountains
        ]);
    }
}
