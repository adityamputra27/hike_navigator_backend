<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('users.index');
    }

    public function datatables(Request $request)
    {
        $users = User::orderBy('created_at', 'DESC');
        return DataTables::of($users)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return date('d-M-Y H:i:s', strtotime($row->created_at));
            })
            ->editColumn('status', function ($row) {
                $html = '';
                $html .= '<span class="badge badge-success">'.$row->status.'</span>';
                return $html;
            })
            ->editColumn('role', function ($row) {
                $html = '';
                if ($row->role == 'ADMIN') {
                    $html .= '<span class="badge badge-info">ADMIN</span>';
                } else {
                    $html .= '<span class="badge badge-danger">HIKER</span>';
                }
                return $html;
            })
            ->addColumn('register_type', function ($row) {
                $html = '';
                if ($row->role == 'ADMIN') {
                    $html .= '<span class="badge badge-info">WEB</span>';
                } else {
                    $html .= '<span class="badge badge-danger">ANDROID</span>';
                }
                return $html;
            })
            ->addColumn('action', function ($row) {
                $button = '<div class="btn-group">';
                $button .= '<a href="'.route('users.edit', $row->id).'" class="btn btn-warning"><i class="fa fa-edit"></i>&nbsp;Edit</a>';
                
                if ($row->role != 'ADMIN') {
                    $button .= '<a href="'.route('users.destroy', $row->id).'" class="btn btn-danger"><i class="fa fa-trash"></i>&nbsp;Delete</a>';
                }

                $button .= '</div>';

                return $button;
            })
            ->rawColumns(['role', 'status', 'register_type', 'action'])
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
        $user = User::findOrFail($id);
        return view('users.edit', ['user' => $user]);
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
