<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peak;
use Yajra\DataTables\DataTables;

class PeakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('peaks.index');
    }

    public function datatables(Request $request)
    {
        $peaks = Peak::orderBy('name', 'ASC');
        return DataTables::of($peaks)
            ->addIndexColumn()
            ->editColumn('status', function ($row) {
                $html = '';
                if ($row->status == 'ACTIVE') {
                    $html .= '<span class="badge badge-success">'.$row->status.'</span>';
                } else {
                    $html .= '<span class="badge badge-danger">'.$row->status.'</span>';
                }
                return $html;
            })
            ->addColumn('action', function ($row) {
                $button = '<div class="btn-group">';
                $button .= '<a href="'.route('peaks.edit', $row->id).'" class="btn btn-warning"><i class="fa fa-edit"></i>&nbsp;Edit</a>';

                $button .= '<form action="'.route('peaks.destroy', $row->id).'" method="POST" class="delete">';
                $button .= csrf_field();
                $button .= '<input type="hidden" name="_method" value="DELETE"/>';
                $button .= '<button class="btn btn-danger" type="submit">Delete</button>';
                $button .= '</form>';

                $button .= '</div>';

                return $button;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('peaks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $peak = new Peak;
        $peak->name = $request->name;
        $peak->height = $request->height;
        $peak->status = $request->status;
        $peak->description = $request->description;
        $peak->user_id = auth()->user()->id;

        if ($peak->save()) {
            return redirect()->route('peaks.index')->with('status', 'Successfully create new peak!');
        }
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
        $peak = Peak::findOrFail($id);
        return view('peaks.edit', ['peak' => $peak]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $peak = Peak::where('id', $id)->first();
        $peak->name = $request->name;
        $peak->height = $request->height;
        $peak->status = $request->status;
        $peak->description = $request->description;
        $peak->user_id = auth()->user()->id;

        if ($peak->save()) {
            return redirect()->route('peaks.index')->with('status', 'Successfully updated peak!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $peak = Peak::findOrFail($id);
        if ($peak->delete()) {
            return redirect()->route('peaks.index')->with('status', 'Successfully deleted peak!');
        }
    }
}
