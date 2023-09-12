@extends('layouts.global')
@section("title") Create Mountain @endsection

@section('content')
<form action="{{ route('mountains.store') }}" method="POST">
    @csrf
    <div class="row pl-3 mb-3">
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
                                <input type="text" name="name" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Height</label>
                                <input type="text" name="height" id="height" class="form-control form-control-sm" placeholder="cth: 1000 - 2000 mdpl" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Province</label>
                                <select name="province_id" id="province_id" class="form-control form-control-sm" required>
                                    <option value="">-</option>
                                    @foreach ($provinces as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">City</label>
                                <select name="city_id" id="city_id" class="form-control form-control-sm" required></select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Description</label>
                        <textarea name="description" id="description" class="form-control form-control-sm" required></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-white pb-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Location</h5>
                    <div>
                        <a href="{{ route('mountains.index') }}" id="reload" class="btn btn-primary btn-sm"><i class="oi oi-chevron-left"></i> Back</a>
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
                                <input type="text" name="latitude" id="latitude" class="form-control form-control-sm" required readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Longitude</label>
                                <input type="text" name="longitude" id="longitude" class="form-control form-control-sm" required readonly>
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
            $('#peak_id, #province_id, #city_id').select2()

            $('#province_id').on('change', function () {
                let provinceId = $(this).val()
                let route = '{{ route("location.getCities", ":provinceId") }}'
                route = route.replace(':provinceId', provinceId)

                $.ajax({
                    method: 'GET',
                    dataType: 'json',
                    url: route,
                    success:function ($response) {
                        $('#city_id').empty()
                        $response.forEach((e, i) => {
                            $('#city_id').append(`<option value="${e.id}">${e.name}</option>`)
                        });
                    }
                })
            })
        })
    </script>
@endsection