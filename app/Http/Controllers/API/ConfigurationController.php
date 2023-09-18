<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Province,
};

class ConfigurationController extends Controller
{
    public function getActiveProvinces(Request $request)
    {
        $provinces = Province::orderBy('name', 'ASC')->get();
        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => $provinces
        ]);
    }
}
