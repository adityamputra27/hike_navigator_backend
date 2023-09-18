<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    ClimbingPlan
};

class ClimbingPlanController extends Controller
{
    public function create(Request $request)
    {
        return $request;
    }

    public function getActiveUser(Request $request, $userId)
    {
        $provinceId = $request->get('provinceId');
        $keyword = $request->get('keyword');

        if (empty($userId)) {
            return response()->json([
                'status' => 500,
                'message' => 'Params user id null'
            ]);
        }

        if ($provinceId) {
            $climbingPlans = ClimbingPlan::with(['user', 'mountain'])
                                    ->whereHas('mountain', function ($query) use ($provinceId, $keyword) {
                                        $query->where('province_id', $provinceId);
                                        $query->where('name', 'LIKE', "%$keyword%");
                                    })
                                    ->where('user_id', $userId)->where('status', 'ACTIVE')->get();
        } else {
            $climbingPlans = ClimbingPlan::with(['user', 'mountain'])
                                    ->whereHas('mountain', function ($query) use ($keyword) {
                                        $query->where('name', 'LIKE', "%$keyword%");
                                    })
                                    ->where('user_id', $userId)->where('status', 'ACTIVE')->get();
        }

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => $climbingPlans
        ]);
    }

    public function getSavedUser(Request $request, $userId)
    {
        $provinceId = $request->get('provinceId');
        $keyword = $request->get('keyword');

        if (empty($userId)) {
            return response()->json([
                'status' => 500,
                'message' => 'Params user id null'
            ]);
        }

        if ($provinceId) {
            $climbingPlans = ClimbingPlan::with(['user', 'mountain'])
                                    ->whereHas('mountain', function ($query) use ($provinceId, $keyword) {
                                        $query->where('province_id', $provinceId);
                                        $query->where('name', 'LIKE', "%$keyword%");
                                    })
                                    ->where('user_id', $userId)->where('status', 'SAVED')->get();
        } else {
            $climbingPlans = ClimbingPlan::with(['user', 'mountain'])
                                    ->whereHas('mountain', function ($query) use ($keyword) {
                                        $query->where('name', 'LIKE', "%$keyword%");
                                    })
                                    ->where('user_id', $userId)->where('status', 'SAVED')->get();
        }

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => $climbingPlans
        ]);
    }
}
