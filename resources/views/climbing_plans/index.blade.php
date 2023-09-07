@extends('layouts.global')
@section("title") List of Climbing Plan Users @endsection

@section('content')
<div class="row pl-3 mb-5">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-white pb-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Table</h5>
                <div>
                    <a href="#" id="reload" class="btn btn-success"><i class="oi oi-reload"></i> Refresh Table</a>
                </div>
            </div>
            <div class="card-body">
                <table id="datatables" class="table table-striped table-hovered table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Fullname</th>
                            <th>Schedule Date</th>
                            <th>Destination</th>
                            <th>Is Map Download</th>
                            <th>Status</th>
                            <th>Cancel?</th>
                            <th>GPS</th>
                            <th>Finished?</th>
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
                    url: "{{ route('climbing_plans.datatables') }}",
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
                        data: 'user.fullname',
                        name: 'user.fullname'
                    },
                    {
                        data: 'schedule_date',
                        name: 'schedule_date'
                    },
                    {
                        data: 'mountain.name',
                        name: 'mountain.name'
                    },
                    {
                        data: 'is_map_download',
                        name: 'is_map_download'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'is_cancel',
                        name: 'is_cancel'
                    },
                    {
                        data: 'gps',
                        name: 'gps'
                    },
                    {
                        data: 'status_finished',
                        name: 'status_finished'
                    },
                ],
            })

            $('#reload').on('click', function () {
                table.ajax.reload()
            })
        })
    </script>
@endsection