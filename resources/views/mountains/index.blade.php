@extends('layouts.global')
@section("title") Manage Destinations @endsection

@section('content')
<div class="row ml-3">
    @if (session('status'))
        <div class="alert alert-success">
            <i class="oi oi-circle-check"></i> <strong>{{ session('status') }}</strong>
        </div>
    @endif
</div>
<div class="row pl-3 mb-5">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-white pb-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Table</h5>
                <div>
                    <a href="{{ route('mountains.create') }}" class="btn btn-primary"><i class="oi oi-plus"></i> Create New</a>
                    <a href="#" id="reload" class="btn btn-success"><i class="oi oi-reload"></i> Refresh Table</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatables" class="table table-striped table-hovered table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Height</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Province</th>
                                <th>City</th>
                                <th>Map Offline</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@include('mountains.peaks')
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
                    url: "{{ route('mountains.datatables') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
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
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'height',
                        name: 'height'
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
                        data: 'province.name',
                        name: 'province.name'
                    },
                    {
                        data: 'city.name',
                        name: 'city.name'
                    },
                    {
                        data: 'is_map_offline',
                        name: 'is_map_offline'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
            })

            $('#reload').on('click', function () {
                table.ajax.reload()
            })

            $('#mountain_peak_id').select2()
        })
    </script>
@endsection