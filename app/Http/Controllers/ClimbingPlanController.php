<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\{
    ClimbingPlan
};

class ClimbingPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('climbing_plans.index');
    }

    public function datatables(Request $request)
    {
        $climbingPlans = ClimbingPlan::with(['user', 'mountain'])->orderBy('created_at', 'DESC');
        return DataTables::of($climbingPlans)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return date('d-M-Y H:i:s', strtotime($row->created_at));
            })
            ->editColumn('status', function ($row) {
                $html = '';
                $html .= '<span class="badge badge-success">'.$row->status.'</span>';
                return $html;
            })
            ->rawColumns(['status'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
