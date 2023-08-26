@extends('layouts.global')
@section("title") Edit Peak @endsection

@section('content')
<form action="{{ route('peaks.update', [$peak->id]) }}" method="POST">
    @csrf
    @method('PATCH')
    <div class="row pl-3 mb-5">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-white pb-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Form</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" name="name" class="form-control form-control-sm" required value="{{ $peak->name }}">
                    </div>
                    <div class="form-group">
                        <label for="">Height</label>
                        <input type="text" name="height" id="height" class="form-control form-control-sm" placeholder="cth: 1000 - 2000 mdpl" required value="{{ $peak->height }}">
                    </div>
                    <div class="form-group">
                        <label for="">Status</label>
                        <div>
                            <input type="radio" name="status" id="active" {{ $peak->status == 'ACTIVE' ? 'checked' : '' }} value="ACTIVE" required>
                            <label for="active">ACTIVE</label>

                            <input type="radio" name="status" id="inactive" {{ $peak->status == 'INACTIVE' ? 'checked' : '' }} value="INACTIVE" required>
                            <label for="inactive">INACTIVE</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Description</label>
                        <textarea name="description" id="description" class="form-control form-control-sm" required>{{ $peak->description }}</textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-lg">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
@section('scripts')
    <script>
        $(function () {
            
        })
    </script>
@endsection