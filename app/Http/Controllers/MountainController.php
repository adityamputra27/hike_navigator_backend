<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    Province,
    Mountain,
    Peak,
    MountainPeak,
};
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class MountainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function datatables()
    {
        $mountains = Mountain::with(['province', 'city']);
        return DataTables::of($mountains)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $button = '<div class="btn-group">';
                    $button .= '<a href="#" class="btn btn-sm btn-info" data-mountain_id="'.$row->id.'" data-toggle="modal" data-target="#peaksModal"><i class="oi oi-plus"></i>&nbsp;Route</a>';
                    $button .= '<a href="'.route('mountains.edit', $row->id).'" class="btn btn-sm btn-primary"><i class="oi oi-image"></i>&nbsp;Image</a>';
                    $button .= '<a href="'.route('mountains.edit', $row->id).'" class="btn btn-sm btn-warning"><i class="oi oi-pencil"></i>&nbsp;Edit</a>';
                    $button .= '<a href="#" data-route="'.route('mountains.destroy', $row->id).'" class="btn btn-sm btn-danger delete"><i class="oi oi-trash"></i>&nbsp;Delete</a>';
                    $button .= '</div>';
    
                    return $button;
                })
                ->rawColumns(['action'])
                ->toJson();
    }

    public function peakDatatables(Request $request)
    {
        $mountains = MountainPeak::with(['peak'])->where('mountain_id', $request->mountain_id);
        return DataTables::of($mountains)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $button = '<div class="btn-group">';
                    $button .= '<a href="'.route('mountains.detailPeak', [$row->mountain_id, $row->peak_id]).'" class="btn btn-sm btn-info"><i class="oi oi-eye"></i>&nbsp;Detail</a>';
                    $button .= '<a href="#" data-route="'.route('mountains.destroyPeak', $row->id).'" class="btn btn-sm btn-danger deletePeak"><i class="oi oi-trash"></i>&nbsp;Delete</a>';
                    $button .= '</div>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->toJson();
    }

    public function storePeaks(Request $request)
    {
        $exist = MountainPeak::where('mountain_id', $request->mountain_id)->where('peak_id', $request->peak_id)->first();
        if (!empty($exist)) {
            return response('failed');
        }

        $mountainPeak = new MountainPeak;
        $mountainPeak->mountain_id = $request->mountain_id;
        $mountainPeak->peak_id = $request->peak_id;
        $mountainPeak->status = 'ACTIVE';
        $mountainPeak->user_id = Auth::id();
        $mountainPeak->save();

        return response('success');
    }

    public function detailPeak(Request $request, $mountainId, $peakId)
    {
        $mountainPeak = MountainPeak::with(['mountain.province', 'mountain.city', 'peak'])
                                    ->where('mountain_id', $mountainId)->where('peak_id', $peakId)->first();
        return view('mountains.detail', ['mountainPeak' => $mountainPeak]);
    }

    public function destroyPeak(Request $request, $id)
    {
        $mountainPeak = MountainPeak::findOrFail($id);
        $mountainPeak->delete();

        return response('success');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {        
        $peaks = Peak::where('status', 'ACTIVE')->get();
        return view('mountains.index', ['peaks' => $peaks]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $provinces = Province::orderBy('name', 'ASC')->get();
        return view('mountains.create', ['provinces' => $provinces]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $mountain = new Mountain();
        $mountain->name = $request->name;
        $mountain->height = $request->height;
        $mountain->latitude = $request->latitude;
        $mountain->longitude = $request->longitude;
        $mountain->province_id = $request->province_id;
        $mountain->city_id = $request->city_id;
        $mountain->description = $request->description;
        $mountain->status = 'ACTIVE';
        $mountain->is_map_offline = 'AVAILABLE';
        $mountain->user_id = Auth::id();

        if ($mountain->save()) {
            return redirect()->route('mountains.index')->with('status', 'Successfully create new destination!');
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
        $mountain = Mountain::findOrFail($id);
        $provinces = Province::orderBy('name', 'ASC')->get();
        return view('mountains.edit', ['mountain' => $mountain, 'provinces' => $provinces]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $mountain = Mountain::findOrFail($id);
        $mountain->name = $request->name;
        $mountain->height = $request->height;
        $mountain->latitude = $request->latitude;
        $mountain->longitude = $request->longitude;
        $mountain->province_id = $request->province_id;
        $mountain->city_id = $request->city_id;
        $mountain->description = $request->description;
        $mountain->status = $request->status;
        $mountain->is_map_offline = 'AVAILABLE';
        $mountain->user_id = Auth::id();

        if ($mountain->save()) {
            return redirect()->route('mountains.index')->with('status', 'Successfully updated destination!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mountain = Mountain::findOrFail($id);
        $mountain->mountainPeaks()->delete();
        $mountain->delete();

        return response('success');
    }
}
