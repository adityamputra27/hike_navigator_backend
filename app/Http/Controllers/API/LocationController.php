<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;

class LocationController extends Controller
{
    public function getCities($provinceId)
    {
        $cities = City::where('province_id', $provinceId)->get();
        return response($cities);
    }
}
