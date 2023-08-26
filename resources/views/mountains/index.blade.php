@extends('layouts.global')
@section("title") Manage Mountains @endsection

@section('content')
@include('master')
<hr class="my-3">
@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif
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
                <table id="datatables" class="table table-striped table-hovered table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Latitude</th>
                            <th>City</th>
                            <th>Province</th>
                            <th>Height</th>
                            <th>Status</th>
                            <th>Created at</th>
                            <th>Action</th>
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
            // const table = $('#datatables').DataTable({
            //     displayLength: 10,
            //     processing: true,
            //     destroy: true,
            //     serverSide: true,
            //     responsive: true,
            //     ajax: {
            //         url: "{{ route('users.datatables') }}",
            //         type: "POST",
            //         data: {
            //             _token: "{{ csrf_token() }}"
            //         }
            //     },
            //     columns: [
            //         {
            //             data: 'DT_RowIndex',
            //             name: 'DT_RowIndex',
            //             width: '1%',
            //             class: 'text-center',
            //             orderable: false,
            //             searchable: false
            //         },
            //         {
            //             data: 'name',
            //             name: 'name'
            //         },
            //         {
            //             data: 'email',
            //             name: 'email'
            //         },
            //         {
            //             data: 'username',
            //             name: 'username'
            //         },
            //         {
            //             data: 'avatar',
            //             name: 'avatar'
            //         },
            //         {
            //             data: 'role',
            //             name: 'role'
            //         },
            //         {
            //             data: 'status',
            //             name: 'status'
            //         },
            //         {
            //             data: 'register_type',
            //             name: 'register_type'
            //         },
            //         {
            //             data: 'created_at',
            //             name: 'created_at'
            //         },
            //         // {
            //         //     data: 'action',
            //         //     name: 'action',
            //         //     orderable: false,
            //         //     searchable: false,
            //         //     className: 'text-center'
            //         // }
            //     ],
            // })

            $('#reload').on('click', function () {
                table.ajax.reload()
            })
        })
    </script>
@endsection