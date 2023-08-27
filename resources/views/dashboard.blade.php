@extends('layouts.global')
@section("title") Dashboard @endsection

@section('content')
<div class="row">
    <div class="col-md-12 pl-3">
        <div class="row pl-3">
            <div class="col-md-6 col-lg-3 col-12 mb-2 col-sm-6">
                <div class="media shadow-sm p-0 bg-white rounded text-primary ">
                    <span class="oi top-0 rounded-left bg-primary text-light h-100 p-4 oi-badge fs-5"></span>
                        <div class="media-body p-2">
                        <h5 class="media-title m-0">Rencana Pendakian</h5>
                        <div class="media-text">
                            <h3>{{ $climbingPlan }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 col-12 mb-2 col-sm-6">
                <div class="media shadow-sm p-0 bg-white rounded text-primary ">
                    <span class="oi top-0 rounded-left bg-warning text-light h-100 p-4 oi-badge fs-5"></span>
                        <div class="media-body p-2">
                        <h5 class="media-title m-0">Semua Destinasi</h5>
                        <div class="media-text">
                            <h3>{{ $mountain }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 col-12 mb-2 col-sm-6">
                <div class="media shadow-sm p-0 bg-white rounded text-primary ">
                    <span class="oi top-0 rounded-left bg-success text-light h-100 p-4 oi-badge fs-5"></span>
                        <div class="media-body p-2">
                        <h5 class="media-title m-0">Semua Pengguna Aktif</h5>
                        <div class="media-text">
                            <h3>{{ $user }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 col-12 mb-2 col-sm-6">
                <div class="media shadow-sm p-0 bg-white rounded text-primary ">
                    <span class="oi top-0 rounded-left bg-danger text-light h-100 p-4 oi-badge fs-5"></span>
                        <div class="media-body p-2">
                        <h5 class="media-title m-0">Semua Lokasi Puncak</h5>
                        <div class="media-text">
                            <h3>{{ $peak }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
