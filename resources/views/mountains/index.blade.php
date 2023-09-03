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

            let mountainId = 0
            const peakTable = $('#peakDatatables').DataTable({
                displayLength: 10,
                processing: true,
                destroy: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('mountains.peakDatatables') }}",
                    type: "POST",
                    data: function (data) {
                        data._token = "{{ csrf_token() }}",
                        data.mountain_id = mountainId
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
                        data: 'peak.name',
                        name: 'peak.name'
                    },
                    {
                        data: 'peak.latitude',
                        name: 'peak.latitude'
                    },
                    {
                        data: 'peak.longitude',
                        name: 'peak.longitude'
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
                        className: 'text-center',
                        width: '1%',
                    }
                ],
            })

            $('#reload').on('click', function () {
                table.ajax.reload()
            })
            $('#peak_id').select2()
            $('#peaksModal').on('show.bs.modal', function (event) {
                $('#peak_id').val('').change()
                let button = $(event.relatedTarget)
                let id = button.data('mountain_id')
                mountainId = id
                peakTable.draw()
            })

            $('#mountainPeaksForm').on('submit', function (event) {
                event.preventDefault()
                let peakId = $(this).find('#peak_id').val()
                if (peakId == '') return
                
                $.post("{{ route('mountains.storePeaks') }}", 
                    { mountain_id: mountainId, peak_id: peakId, _token: "{{ csrf_token() }}" }, (data, status) => {
                    if (data == 'success') {
                        Swal.fire({
                            title: 'Successfully create new mountain peaks!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                peakTable.ajax.reload()
                            }
                        })
                    } else {
                        Swal.fire({
                            title: 'Mountain peaks already exist!',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        })
                    }
                })
            })

            $(document).on('click', '.delete', async function (e) {
                e.preventDefault()
                let route = $(this).data('route')
                await Swal.fire({
                    title: 'Are you sure?',
                    text: "Delete this data?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({ url: route, method: 'DELETE', data: { _token: "{{ csrf_token() }}", }, success: ($response) => {
                            if ($response == 'success') {
                                Swal.fire({
                                    title: 'Successfully delete mountain!',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        table.ajax.reload()
                                    }
                                })
                            }
                        } })
                    }
                })
            })

            $(document).on('click', '.deletePeak', async function (e) {
                e.preventDefault()
                let route = $(this).data('route')
                await Swal.fire({
                    title: 'Are you sure?',
                    text: "Delete this data?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({ url: route, method: 'DELETE', data: { _token: "{{ csrf_token() }}", }, success: ($response) => {
                            if ($response == 'success') {
                                Swal.fire({
                                    title: 'Successfully delete mountain peak!',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        peakTable.ajax.reload()
                                    }
                                })
                            }
                        } })
                    }
                })
            })
        })
    </script>
@endsection