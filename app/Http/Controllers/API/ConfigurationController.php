<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Province,
    Mountain,
    Setting,
};

class ConfigurationController extends Controller
{
    public function getActiveProvinces(Request $request)
    {
        $provinceExists = Mountain::orderBy('name', 'ASC')->groupBy('province_id')->pluck('province_id');
        if (!empty($provinceExists)) {
            $provinces = Province::orderBy('name', 'ASC')->whereIn('id', $provinceExists)->get();
        } else {
            $provinces = Province::orderBy('name', 'ASC')->whereIn('id', [0])->get();
        }

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => $provinces
        ]);
    }

    public function getSettings(Request $request)
    {
        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => Setting::first()
        ]);
    }
}
