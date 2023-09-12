@extends('layouts.global')
@section("title") Edit User @endsection

@section('content')
<div class="row pl-3 mb-5">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-white pb-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form</h5>
                <div>
                    <a href="{{ route('users.index') }}" id="reload" class="btn btn-primary"><i class="oi oi-chevron-left"></i> Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('users.update', [$user->id]) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label for="">Fullname</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Username</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Role</label>
                        <input type="text" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="">Status</label>
                        <div>
                            <input type="radio" name="status" id="active" value="ACTIVE">
                            <label for="active">ACTIVE</label>

                            <input type="radio" name="status" id="inactive" value="INACTIVE">
                            <label for="inactive">INACTIVE</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Avatar</label>
                        <input type="file" name="avatar" class="form-control">
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary btn-lg">Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        $(function () {
            
        })
    </script>
@endsection