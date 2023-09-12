<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    ClimbingPlan,
    Mountain,
    User,
    Peak
};

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $climbingPlan = ClimbingPlan::where('status', 'ACITVE')->count();
        $mountain = Mountain::where('status', 'ACTIVE')->count();
        $user = User::where('status', 'ACTIVE')->count();
        $peak = Peak::where('status', 'ACTIVE')->count();

        return view('dashboard', [
            'climbingPlan' => $climbingPlan,
            'mountain' => $mountain,
            'user' => $user,
            'peak' => $peak,
        ]);
    }
}
