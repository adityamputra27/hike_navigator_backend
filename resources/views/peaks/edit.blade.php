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
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Name</label>
                                <input type="text" name="name" class="form-control form-control-sm" required value="{{ $peak->name }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Height</label>
                                <input type="text" name="height" id="height" class="form-control form-control-sm" placeholder="cth: 1000 - 2000 mdpl" required value="{{ $peak->height }}">
                            </div>
                        </div>
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
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-white pb-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Location</h5>
                    <div>
                        <a href="{{ route('peaks.index') }}" id="reload" class="btn btn-primary btn-sm"><i class="oi oi-chevron-left"></i> Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="">Choose Location</label>
                        <button class="btn btn-success" type="button" data-toggle="modal" data-target="#mapModal">Show Maps</button>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Latitude</label>
                                <input type="text" name="latitude" id="latitude" class="form-control form-control-sm" required readonly value="{{ $peak->latitude }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Longitude</label>
                                <input type="text" name="longitude" id="longitude" class="form-control form-control-sm" required readonly value="{{ $peak->longitude }}">
                            </div>
                        </div>
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
@include('maps')
@section('scripts')
    <script>
        $(function () {
            
        })
    </script>
@endsection