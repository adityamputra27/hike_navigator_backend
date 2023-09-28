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
    public function index(Request $request)
    {
        $provinceId = $request->get('provinceId');
        $keyword = $request->get('keyword');
        
        if ($provinceId) {
            $mountains = Mountain::with([
                'province', 'city', 'mountainImages', 'mountainPeaks.mountain', 
                'mountainPeaks.peak', 'mountainPeaks.tracks',
                'mountainPeaks.tracks.marks', 'mountainPeaks.tracks.waterfalls', 
                'mountainPeaks.tracks.watersprings', 'mountainPeaks.tracks.rivers', 
                'mountainPeaks.tracks.posts', 'mountainTracks', 'mountainMarks', 'mountainWaterfalls',
                'mountainWatersprings', 'mountainRivers', 'mountainPosts'
            ])
            ->whereHas('mountainPeaks.tracks', function ($query) {
                $query->select('id');
            })
            ->where('province_id', $provinceId)
            ->where('name', 'LIKE', "%$keyword%")
            ->orderBy('name', 'ASC')->get();
        } else {
            $mountains = Mountain::with([
                'province', 'city', 'mountainImages', 'mountainPeaks.mountain', 
                'mountainPeaks.peak', 'mountainPeaks.tracks',
                'mountainPeaks.tracks.marks', 'mountainPeaks.tracks.waterfalls', 
                'mountainPeaks.tracks.watersprings', 'mountainPeaks.tracks.rivers', 
                'mountainPeaks.tracks.posts', 'mountainTracks', 'mountainMarks', 'mountainWaterfalls',
                'mountainWatersprings', 'mountainRivers', 'mountainPosts'
            ])
            ->where('name', 'LIKE', "%$keyword%")
            ->orderBy('name', 'ASC')->get();
        }

        return response()->json([
            'message' => 'success',
            'data' => $mountains
        ]);
    }
}
