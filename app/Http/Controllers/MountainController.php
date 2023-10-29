<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    Province,
    Mountain,
    Peak,
    MountainPeak,
    MountainImage,
    Track
};
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Storage;

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
                    $button .= '<a href="'.route('mountains.uploadImages', $row->id).'" class="btn btn-sm btn-primary"><i class="oi oi-image"></i>&nbsp;Image</a>';
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
                ->editColumn('status', function ($row) {
                    $html = '';
                    $html .= '<span class="badge badge-success">'.$row->status.'</span>';
                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $button = '<div class="btn-group">';
                    $button .= '<a href="'.route('mountains.detailPeak', [$row->mountain_id, $row->peak_id]).'" class="btn btn-sm btn-info"><i class="oi oi-eye"></i>&nbsp;Detail</a>';
                    $button .= '<a href="#" data-route="'.route('mountains.destroyPeak', $row->id).'" class="btn btn-sm btn-danger deletePeak"><i class="oi oi-trash"></i>&nbsp;Delete</a>';
                    $button .= '</div>';
                    return $button;
                })
                ->rawColumns(['status', 'action'])
                ->toJson();
    }

    public function trackDatatables(Request $request)
    {
        $tracks = Track::with(['waterfalls', 'watersprings', 'rivers', 'posts'])->where('mountain_peak_id', $request->mountain_peak_id);
        return DataTables::of($tracks)
                ->addIndexColumn()
                ->editColumn('geojson', function ($row) {
                    return Str::limit(strip_tags($row->geojson), 100);
                })
                ->addColumn('detail', function ($row) {
                    $html = '';
                    $html .= '<img class="mr-2" width="25" src="'.asset('images/posts.png').'"/>';
                    $html .= '<b>Total Posts : '.count($row->posts).'</b>';
                    $html .= '<br />';

                    $html .= '<img class="mr-2" width="25" src="'.asset('images/rivers.png').'"/>';
                    $html .= '<b>Total Rivers : '.count($row->rivers).'</b>';
                    $html .= '<br />';

                    $html .= '<img class="mr-2" width="25" src="'.asset('images/waterfalls.png').'"/>';
                    $html .= '<b>Total Waterfalls : '.count($row->waterfalls).'</b>';
                    $html .= '<br />';

                    $html .= '<img class="mr-2" width="25" src="'.asset('images/watersprings.png').'"/>';
                    $html .= '<b>Total Watersprings : '.count($row->watersprings).'</b>';
                    $html .= '<br />';

                    return $html;
                })
                ->editColumn('status', function ($row) {
                    $html = '';
                    $html .= '<span class="badge badge-success">'.$row->status.'</span>';
                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $mountainPeak = MountainPeak::findOrFail($row->mountain_peak_id);

                    $button = '<div class="btn-group">';
                    $button .= '<a href="'.route('mountains.editTrack', [$mountainPeak->mountain_id, $mountainPeak->peak_id, $row->id]).'" class="btn btn-sm btn-warning"><i class="oi oi-pencil"></i>&nbsp;Edit</a>';
                    $button .= '<a href="#" data-route="'.route('mountains.destroyTrack', [$mountainPeak->mountain_id, $mountainPeak->peak_id, $row->id]).'" class="btn btn-sm btn-danger delete"><i class="oi oi-trash"></i>&nbsp;Delete</a>';
                    $button .= '</div>';
                    return $button; 
                })
                ->rawColumns(['detail', 'status', 'action'])
                ->toJson();
    }

    public function fetchImages(Request $request, $id)
    {
        $mountainImages = MountainImage::where('mountain_id', $id)->pluck('image')->toArray();
        $storeFolder = storage_path('app/public/mountain-images/');
        $filePath = storage_path('app/public/');
        $files = scandir($storeFolder);

        $data = [];
        foreach ($files as $key => $file) {
            if ($file != '.' && $file != '..' && in_array($file, $mountainImages)) {
                $obj['name'] = $file;
                $filePath = storage_path('app/public/mountain-images/').$file;
                $obj['size'] = filesize($filePath);
                $obj['path'] = Storage::url('mountain-images/'.$file);
                $data[] = $obj;
            }
        }

        return response()->json($data);
    }

    public function uploadImages($id)
    {
        $mountain = Mountain::findOrFail($id);

        $data['route']['fetchImages'] = route('mountains.fetchImages', $id);
        $data['route']['storeImages'] = route('mountains.storeImages', $id);
        $data['route']['deleteImages'] = route('mountains.deleteImages', $id);
        return view('mountains.upload', [
            'mountain' => $mountain,
            'route' => $data['route']
        ]);
    }

    public function storeImages(Request $request, $id)
    {
        $image = $request->file('file');
        $fileInfo = $image->getClientOriginalName();
        $filename = pathinfo($fileInfo, PATHINFO_FILENAME);
        $extension = pathinfo($fileInfo, PATHINFO_EXTENSION);

        $fileName = $filename.'-'.time().'.'.$extension;
        $image->store('mountain-images', 'public');

        $mountainImages = new MountainImage;
        $mountainImages->mountain_id = $id;
        $mountainImages->original_filename = $fileInfo;
        $mountainImages->image = $image->hashName();
        $mountainImages->filename = $fileName;
        $mountainImages->user_id = Auth::id();
        $mountainImages->save();
        
        return response()->json([
            'success' => $fileName
        ]);
    }

    public function deleteImages(Request $request, $id)
    {
        $image = $request->image;
        MountainImage::where('mountain_id', $id)->where('image', $image)->delete();

        $path = storage_path('app/public/mountain-images/'.$image);
        if (file_exists($path)) {
            Storage::delete('public/mountain-images/'.$image);
        }
        return response()->json(['success' => $image]);
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

    public function createTrack(Request $request, $mountainId, $peakId)
    {
        $mountainPeak = MountainPeak::with(['mountain.province', 'mountain.city', 'peak'])
                                    ->where('mountain_id', $mountainId)->where('peak_id', $peakId)->first();
        $mountain = $mountainPeak->mountain;
        $peak = $mountainPeak->peak;

        return view('mountains.create-tracks', [
            'mountainPeak' => $mountainPeak,
            'mountain' => $mountain,
            'peak' => $peak,
        ]);
    }

    public function storeTrack(Request $request, $mountainId, $peakId)
    {
        $track = new Track();
        $track->mountain_id = $mountainId;
        $track->mountain_peak_id = $request->mountain_peak_id;
        $track->geojson = $request->geojson_modal;
        $track->coordinates = $request->coordinates_modal;
        $track->start_latitude = $request->start_latitude_modal;
        $track->start_longitude = $request->start_longitude_modal;
        $track->title = $request->title;
        $track->description = $request->description != '' ? $request->description : '-';
        $track->user_id = Auth::id();
        $track->status = 'ACTIVE';
        $track->latitude = '-';
        $track->longitude = '-';

        if ($track->save()) {
            return redirect()->route('mountains.editTrack', [$mountainId, $peakId, $track->id])->with('status', 'Successfully create new track!');
        }
    }

    public function editTrack(Request $request, $mountainId, $peakId, $trackId)
    {
        $track = Track::with(['marks', 'waterfalls', 'watersprings', 'rivers', 'posts'])
                        ->where('id', $trackId)->first();
        if (empty($track)) {
            return redirect()->route('mountains.createTrack', [$mountainId, $peakId]);
        }
        
        $mountainPeak = MountainPeak::with(['mountain', 'peak', 'tracks.waterfalls', 'tracks.rivers', 'tracks.posts', 'tracks.watersprings'])->where('id', $track->mountain_peak_id)->first();

        return view('mountains.edit-tracks', [
            'mountainPeak' => $mountainPeak,
            'mountain' => $mountainPeak->mountain,
            'peak' => $mountainPeak->peak,
            'track' => $track,
        ]);
    }

    public function updateTrack(Request $request, $mountainId, $peakId, $trackId)
    {
        $params['value'] = $request->value;
        $params['mountain_id'] = $mountainId;
        $params['mountain_peak_id'] = $request->mountain_peak_id;
        $params['track_id'] = $trackId;
        $params['request'] = $request;

        $result = $this->createTrackDetails($params);
        if ($result) {
            return redirect()->route('mountains.editTrack', [$mountainId, $peakId, $trackId])->with('status', 'Successfully create new detail track!');
        }
    }

    public function destroyTrack(Request $request, $mountainId, $peakId, $trackId)
    {
        $track = Track::findOrFail($trackId);

        $track->marks()->delete();
        $track->waterfalls()->delete();
        $track->watersprings()->delete();
        $track->rivers()->delete();
        $track->posts()->delete();

        $track->delete();

        return response('success');
    }

    public function createTrackDetails($params = [])
    {
        $request = $params['request'];
        $table = Str::lower($params['value']);

        return DB::table($table)->insert([
            'mountain_id' => $params['mountain_id'],
            'mountain_peak_id' => $params['mountain_peak_id'],
            'track_id' => $params['track_id'],
            'title' => $params['request']->title,
            'latitude' => $params['request']->latitude,
            'longitude' => $params['request']->longitude,
            'contact_number' => $params['request']->contact_number,
            'description' => $params['request']->description != '' ? $params['request']->description : '-',
            'status' => 'ACTIVE',
            'user_id' => Auth::id(),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
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
