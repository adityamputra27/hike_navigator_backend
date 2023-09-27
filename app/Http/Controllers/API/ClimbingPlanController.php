<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    ClimbingPlan,
    Mountain,
    User,
};
use Carbon\Carbon;

class ClimbingPlanController extends Controller
{
    public function create(Request $request)
    {
        $mountain = Mountain::where('id', $request->mountain_id)->first();
        if (!$mountain) {
            return response()->json([
                'status' => 404,
                'message' => "mountain id doesn't exist"
            ]);
        }

        $user = User::where('id', $request->user_id)->first();
        if (!$user) {
            return response()->json([
                'status' => 404,
                'message' => "user id doesn't exist"
            ]);
        }

        if ($request->schedule_date) {
            $scheduleDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->schedule_date);
            $climbingPlanUser = ClimbingPlan::where('user_id', $request->user_id)
                                            ->where('mountain_id', $request->mountain_id)
                                            ->whereDate('schedule_date', $scheduleDate)
                                            ->where('status', $request->status)->first();
        } else {
            $climbingPlanUser = ClimbingPlan::where('user_id', $request->user_id)
                                            ->where('mountain_id', $request->mountain_id)
                                            ->where('status', $request->status)->first();
        }

        
        if ($climbingPlanUser) {
            return response()->json([
                'status' => 500,
                'message' => "can't create schedule with same destination and same day"
            ]);
        }

        $newClimbingPlan = new ClimbingPlan;
        $newClimbingPlan->mountain_id = $request->mountain_id;
        $newClimbingPlan->schedule_date = $request->schedule_date;
        $newClimbingPlan->is_map_download = 'SUCCESS';
        $newClimbingPlan->user_id = $request->user_id;
        $newClimbingPlan->mountain_peak_id = $request->mountain_peak_id;
        $newClimbingPlan->track_id = $request->track_id;
        
        if ($request->status == 'SAVED') {
            $newClimbingPlan->status = 'SAVED';
        } else {
            $newClimbingPlan->status = 'ACTIVE';
        }

        $newClimbingPlan->is_cancel = 0;
        $newClimbingPlan->gps = 'ACTIVE';
        $newClimbingPlan->status_finished = 'PROCESS';
        $newClimbingPlan->save();

        return response()->json([
            'status' => 400,
            'message' => "successfully create new schedule"
        ]);
    }

    public function cancel(Request $request, $climbingPlanId)
    {
        $climbingPlan = ClimbingPlan::findOrFail($climbingPlanId);
        $climbingPlan->is_cancel = 1;
        $climbingPlan->save();

        return response()->json([
            'status' => 400,
            'message' => "successfully cancel schedule"
        ]);
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
            $climbingPlans = ClimbingPlan::with(['user', 'mountain.mountainImages', 'mountain.province', 
                                                'mountain.city', 'mountain.mountainPeaks.mountain', 
                                                'mountain.mountainPeaks.peak', 'mountain.mountainPeaks.tracks',
                                                'mountain.mountainPeaks.tracks.marks', 
                                                'mountain.mountainPeaks.tracks.waterfalls', 
                                                'mountain.mountainPeaks.tracks.watersprings', 
                                                'mountain.mountainPeaks.tracks.rivers', 
                                                'mountain.mountainPeaks.tracks.posts'])
                                    ->whereHas('mountain', function ($query) use ($provinceId, $keyword) {
                                        $query->where('province_id', $provinceId);
                                        $query->where('name', 'LIKE', "%$keyword%");
                                    })
                                    ->where('is_cancel', 0)
                                    ->where('user_id', $userId)->where('status', 'ACTIVE')->get();
        } else {
            $climbingPlans = ClimbingPlan::with(['user', 'mountain.mountainImages', 'mountain.province', 
                                                'mountain.city', 'mountain.mountainPeaks.mountain', 
                                                'mountain.mountainPeaks.peak', 'mountain.mountainPeaks.tracks',
                                                'mountain.mountainPeaks.tracks.marks', 
                                                'mountain.mountainPeaks.tracks.waterfalls', 
                                                'mountain.mountainPeaks.tracks.watersprings', 
                                                'mountain.mountainPeaks.tracks.rivers', 
                                                'mountain.mountainPeaks.tracks.posts'])
                                    ->whereHas('mountain', function ($query) use ($keyword) {
                                        $query->where('name', 'LIKE', "%$keyword%");
                                    })
                                    ->where('is_cancel', 0)
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
            $climbingPlans = ClimbingPlan::with(['user', 'mountain.province', 'mountain.city', 'mountain.mountainImages',
                                    'mountain.mountainPeaks.mountain', 
                                    'mountain.mountainPeaks.peak', 'mountain.mountainPeaks.tracks',
                                    'mountain.mountainPeaks.tracks.marks', 
                                    'mountain.mountainPeaks.tracks.waterfalls', 
                                    'mountain.mountainPeaks.tracks.watersprings', 
                                    'mountain.mountainPeaks.tracks.rivers', 
                                    'mountain.mountainPeaks.tracks.posts'])
                                    ->whereHas('mountain', function ($query) use ($provinceId, $keyword) {
                                        $query->where('province_id', $provinceId);
                                        $query->where('name', 'LIKE', "%$keyword%");
                                    })
                                    ->where('is_cancel', 0)
                                    ->where('user_id', $userId)->where('status', 'SAVED')->get();
        } else {
            $climbingPlans = ClimbingPlan::with(['user', 'mountain.province', 'mountain.city', 'mountain.mountainImages',
                                    'mountain.mountainPeaks.mountain', 
                                    'mountain.mountainPeaks.peak', 'mountain.mountainPeaks.tracks',
                                    'mountain.mountainPeaks.tracks.marks', 
                                    'mountain.mountainPeaks.tracks.waterfalls', 
                                    'mountain.mountainPeaks.tracks.watersprings', 
                                    'mountain.mountainPeaks.tracks.rivers', 
                                    'mountain.mountainPeaks.tracks.posts'])
                                    ->whereHas('mountain', function ($query) use ($keyword) {
                                        $query->where('name', 'LIKE', "%$keyword%");
                                    })
                                    ->where('is_cancel', 0)
                                    ->where('user_id', $userId)->where('status', 'SAVED')->get();
        }

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => $climbingPlans
        ]);
    }
}
