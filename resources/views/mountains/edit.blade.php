@extends('layouts.global')
@section("title") Edit Mountain @endsection

@section('content')
<form action="{{ route('mountains.update', [$mountain->id]) }}" method="POST">
    @csrf
    @method('PATCH')
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
                                <input type="text" name="name" class="form-control form-control-sm" required value="{{ $mountain->name }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Height</label>
                                <input type="text" name="height" id="height" class="form-control form-control-sm" placeholder="cth: 1000 - 2000 mdpl" required value="{{ $mountain->height }}">
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
                                        <option value="{{ $item->id }}" {{ $mountain->province_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
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
                        <label for="">Status</label>
                        <div>
                            <input type="radio" name="status" id="active" value="ACTIVE" {{ $mountain->status == 'ACTIVE' ? 'checked' : '' }}>
                            <label for="active">ACTIVE</label>

                            <input type="radio" name="status" id="inactive" value="INACTIVE" {{ $mountain->status == 'INACTIVE' ? 'checked' : '' }}>
                            <label for="inactive">INACTIVE</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Description</label>
                        <textarea name="description" id="description" class="form-control form-control-sm" required>{{ $mountain->description }}</textarea>
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
                                <input type="text" name="latitude" id="latitude" class="form-control form-control-sm" required readonly value="{{ $mountain->latitude }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Longitude</label>
                                <input type="text" name="longitude" id="longitude" class="form-control form-control-sm" required readonly value="{{ $mountain->longitude }}">
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
@include('mountains.maps')
@section('scripts')
    <script>
        $(function () {
            $('#peak_id, #province_id, #city_id').select2()
            $('#province_id').on('change', function () {
                let provinceId = $(this).val()
                loadCities(provinceId)
            })

            const loadCities = (provinceId) => {
                let route = '{{ route("location.getCities", ":provinceId") }}'
                route = route.replace(':provinceId', provinceId)

                $.ajax({
                    method: 'GET',
                    dataType: 'json',
                    url: route,
                    success:function ($response) {
                        $('#city_id').empty()
                        $response.forEach((e, i) => {
                            let option = $('<option>', {
                                value: e.id,
                                text: e.name
                            })
                            if (e.id == {{ $mountain->city_id }}) {
                                option.attr('selected', 'selected')
                            }
                            $('#city_id').append(option)
                        });
                    }
                })
            }
            loadCities("{{ $mountain->province_id }}")

            mapboxgl.accessToken = 'pk.eyJ1IjoiaGlrZW5hdmlnYXRvcm5ldyIsImEiOiJjbGxoZXRsdnoxOW5wM2ZwamZ2eTBtMWV1In0.jYkxsonNQIn_GsbJorNkEw';
            var map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/streets-v11',
                zoom: 10,
            });

            navigator.geolocation.getCurrentPosition(function (position) {
                let userLocation = [position.coords.longitude, position.coords.latitude]
                map.on('load', function () {
                    map.setCenter(userLocation);
                });

                new mapboxgl.Marker()
                    .setLngLat(userLocation)
                    .addTo(map);
                
                $('#longitude_modal').val(userLocation[0])
                $('#latitude_modal').val(userLocation[1])
            })

            map.addControl(
                new MapboxGeocoder({
                    accessToken: mapboxgl.accessToken,
                    mapboxgl: mapboxgl
                })
            );

            marker = null
            map.on('click', function (e) {
                let latitude = e.lngLat.lat
                let longitude = e.lngLat.lng

                if (marker) {
                    marker.setLngLat([longitude, latitude])
                } else {
                    marker = new mapboxgl.Marker()
                    .setLngLat([longitude, latitude])
                    .addTo(map)
                }
                $('#latitude_modal').val(latitude)
                $('#longitude_modal').val(longitude)
            })

            $('#mapModal').on('shown.bs.modal', function () {
                map.resize();
            })

            $('#mapModal').on('hidden.bs.modal', function () {
                let latitude = $('#latitude_modal').val()
                let longitude = $('#longitude_modal').val()

                $('#latitude').val(latitude)
                $('#longitude').val(longitude)

                if (marker) {
                    marker.remove()
                    marker = null
                }
            })
        })
    </script>
@endsection