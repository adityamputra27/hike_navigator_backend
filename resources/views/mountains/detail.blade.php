@extends('layouts.global')
@section("title") Detail Mountain Peak @endsection

@section('content')
<div class="pl-3 mb-3">
    <a href="{{ route('mountains.index') }}" class="btn btn-primary btn-sm"><i class="oi oi-chevron-left"></i> Back</a>
</div>
<div class="row pl-3 mb-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-white pb-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Mountain</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Name</label>
                            <input type="text" name="name" class="form-control form-control-sm" value="{{ $mountainPeak->mountain->name }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Height</label>
                            <input type="text" name="height" id="height" class="form-control form-control-sm" value="{{ $mountainPeak->mountain->height }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Province</label>
                            <input type="text" name="province_id" id="province_id" class="form-control form-control-sm" value="{{ $mountainPeak->mountain->province->name }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">City</label>
                            <input type="text" name="city_id" id="city_id" class="form-control form-control-sm" value="{{ $mountainPeak->mountain->city->name }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Latitude</label>
                            <input type="text" name="latitude" id="latitude" class="form-control form-control-sm" value="{{ $mountainPeak->mountain->latitude }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Longitude</label>
                            <input type="text" name="longitude" id="longitude" class="form-control form-control-sm" value="{{ $mountainPeak->mountain->longitude }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Description</label>
                    <textarea name="description" id="description" class="form-control form-control-sm" readonly>{{ $mountainPeak->mountain->description }}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-white pb-3">
                <h5 class="mb-0">Peak</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Name</label>
                            <input type="text" name="name" class="form-control form-control-sm" value="{{ $mountainPeak->peak->name }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Height</label>
                            <input type="text" name="height" id="height" class="form-control form-control-sm" value="{{ $mountainPeak->peak->height }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Latitude</label>
                            <input type="text" name="latitude" id="latitude" class="form-control form-control-sm" value="{{ $mountainPeak->peak->latitude }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Longitude</label>
                            <input type="text" name="longitude" id="longitude" class="form-control form-control-sm" value="{{ $mountainPeak->peak->longitude }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Description</label>
                    <textarea name="description" id="description" class="form-control form-control-sm" readonly>{{ $mountainPeak->peak->description }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row pl-3 mb-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-white pb-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">List of Tracks</h5>
                <div>
                    <a href="{{ route('mountains.createTrack', [$mountainPeak->mountain_id, $mountainPeak->peak_id]) }}" class="btn btn-primary"><i class="oi oi-plus"></i> Create New</a>
                    <a href="#" id="reload" class="btn btn-success"><i class="oi oi-reload"></i> Refresh Table</a>
                </div>
            </div> 
            <div class="card-body">
                <table class="table table-hovered table-bordered" id="datatables">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Title</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        $(function () {
            const table = $('#datatables').DataTable({
                displayLength: 10,
                processing: true,
                destroy: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('mountains.trackDatatables') }}",
                    type: "POST",
                    data: function (data) {
                        data._token = "{{ csrf_token() }}",
                        data.mountain_peak_id = "{{ $mountainPeak->id }}"
                    }
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        width: '1%',
                        class: 'text-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'latitude',
                        name: 'latitude'
                    },
                    {
                        data: 'longitude',
                        name: 'longitude'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                ],
            })
        })
    </script>
@endsection