@extends('layouts.global')
@section("title") Create Mountain @endsection

@section('content')
<form action="{{ route('mountains.create') }}" method="POST">
    @csrf
    <div class="row pl-3 mb-5">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-white pb-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Form</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" name="name" class="form-control form-control-sm">
                    </div>
                    <div class="form-group">
                        <label for="">Province</label>
                        <select name="province_id" id="province_id" class="form-control form-control-sm"></select>
                    </div>
                    <div class="form-group">
                        <label for="">City</label>
                        <select name="city_id" id="city_id" class="form-control form-control-sm"></select>
                    </div>
                    <div class="form-group">
                        <label for="">Height</label>
                        <input type="text" name="height" id="height" class="form-control form-control-sm" placeholder="cth: 1000 - 2000 mdpl">
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
                        <label for="">Description</label>
                        <textarea name="description" id="description" class="form-control form-control-sm"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-white pb-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Form</h5>
                    <div>
                        <a href="{{ route('mountains.index') }}" id="reload" class="btn btn-primary btn-sm"><i class="oi oi-chevron-left"></i> Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="">Select Peaks</label>
                        <select name="peak_id" id="peak_id" class="form-control form-control-sm">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Choose Location</label>
                        <button class="btn btn-success" type="button">Show Maps</button>
                    </div>
                    <div class="form-group">
                        <label for="">Latitude</label>
                        <input type="text" name="latitude" id="latitude" class="form-control form-control-sm">
                    </div>
                    <div class="form-group">
                        <label for="">Longitude</label>
                        <input type="text" name="longitude" id="longitude" class="form-control form-control-sm">
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
            $('#peak_id').select2()
            mapboxgl.accessToken = 'pk.eyJ1IjoiaGlrZW5hdmlnYXRvcm5ldyIsImEiOiJjbGxoZXRsdnoxOW5wM2ZwamZ2eTBtMWV1In0.jYkxsonNQIn_GsbJorNkEw';
            var map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/streets-v11'
            });
        })
    </script>
@endsection