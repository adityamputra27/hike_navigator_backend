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
            ->editColumn('schedule_date', function ($row) {
                return date('d-M-Y H:i:s', strtotime($row->schedule_date));
            })
            ->editColumn('is_map_download', function ($row) {
                $html = '';
                if ($row->is_map_download == 'SUCCESS') {
                    $html .= '<span class="badge badge-success">'.$row->is_map_download.'</span>';
                } else if ($row->is_map_download == 'FAILED') {
                    $html .= '<span class="badge badge-danger">'.$row->is_map_download.'</span>';
                }

                return $html;
            })
            ->editColumn('is_cancel', function ($row) {
                $html = '';
                if ($row->is_cancel == 0) {
                    $html .= '<span class="badge badge-danger">NO</span>';
                } else if ($row->is_cancel == 1) {
                    $html .= '<span class="badge badge-success">YES</span>';
                }

                return $html;
            })
            ->editColumn('gps', function ($row) {
                $html = '';
                if ($row->gps == 'ACTIVE') {
                    $html .= '<span class="badge badge-success">'.$row->gps.'</span>';
                } else if ($row->gps == 'INACTIVE') {
                    $html .= '<span class="badge badge-danger">'.$row->gps.'</span>';
                }

                return $html;
            })
            ->editColumn('status_finished', function ($row) {
                $html = '';
                if ($row->status_finished == 'FINISH') {
                    $html .= '<span class="badge badge-info">'.$row->status_finished.'</span>';
                } else if ($row->status_finished == 'PROCESS') {
                    $html .= '<span class="badge badge-primary">'.$row->status_finished.'</span>';
                }

                return $html;
            })
            ->editColumn('status', function ($row) {
                $html = '';
                $html .= '<span class="badge badge-success">'.$row->status.'</span>';
                return $html;
            })
            ->rawColumns(['status', 'is_map_download', 'is_cancel', 'gps', 'status_finished'])
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
