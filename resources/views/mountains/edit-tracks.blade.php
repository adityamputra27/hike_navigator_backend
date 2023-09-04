@extends('layouts.global')
@section("title") Edit Tracks @endsection

@section('content')
<div class="row pl-3">
    <div class="col-lg-12">
        @if (session('status'))
            <div class="alert alert-success">
                <i class="oi oi-circle-check"></i> <strong>{{ session('status') }}</strong>
            </div>
        @endif
    </div>
</div>
<div class="row pl-3 mb-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-white pb-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Choose Location</h5>
                <div>
                    <button type="button" class="btn btn-danger detail" data-value="mark">Create Mark</button>
                    <button type="button" class="btn btn-warning detail" data-value="post">Create Post</button>
                    <button type="button" class="btn btn-success detail" data-value="river">Create River</button>
                    <button type="button" class="btn btn-primary detail" data-value="waterfall">Create Waterfall</button>
                    <button type="button" class="btn btn-info detail" data-value="water_spring">Create Water Spring</button>
                    <a href="#" class="btn btn-secondary"><i class="oi oi-info"></i> Petunjuk Penggunaan</a>
                </div>
            </div>
            <div class="card-body">
                <div id="map" style="width: 100%; height: 500px;"></div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        $(function () {
            mapboxgl.accessToken = 'pk.eyJ1IjoiaGlrZW5hdmlnYXRvcm5ldyIsImEiOiJjbGxoZXRsdnoxOW5wM2ZwamZ2eTBtMWV1In0.jYkxsonNQIn_GsbJorNkEw';
            let map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/streets-v11',
                zoom: 10,
                center: ["{{ $mountain->longitude }}", "{{ $mountain->latitude }}"],
            });

            let routeTrack = JSON.parse(decodeURIComponent("{{ rawurlencode($track->geojson) }}"))
            if (routeTrack !== 'undefined' && routeTrack !== null) {
                map.on('load', function () {
                    map.addSource('routeTrack', {
                        type: 'geojson',
                        data: routeTrack
                    })
                    map.addLayer({
                        id: 'routeTrack',
                        type: 'line',
                        source: 'routeTrack',
                        layout: {
                            'line-join': 'round',
                            'line-cap': 'round',
                        },
                        paint: {
                            'line-color': 'red',
                            'line-width': 3,
                            'line-dasharray': [2, 2]
                        },
                    })
                })
            }

            let geocoder = new MapboxGeocoder({
                accessToken: mapboxgl.accessToken,
                mapboxgl: mapboxgl,
                marker: {
                    color: 'orange'
                },
                placeholder: 'Cari lokasi...'
            });

            map.addControl(geocoder);
            geocoder.on('result', function (e) {
                let searchResult = e.result;
                let userLocation = searchResult.geometry.coordinates;
                map.setCenter(userLocation);
            });

            // set mountain and peak selected
            let mountainLongitude = "{{ $mountain->longitude }}"
            let mountainLatitude = "{{ $mountain->latitude }}"
            if (mountainLongitude != '' && mountainLatitude != '') {
                const mountainMarker = new mapboxgl.Marker({ color: 'green', scale: 1 })
                    .setLngLat({lng: mountainLongitude, lat: mountainLatitude})
                    .addTo(map)
                
                const mountainPopup = new mapboxgl.Popup().setHTML(`
                                                                    <b>{{ $mountain->name }}</b> {{ $mountain->height }}
                                                                    <p class="mb-0">Lat: {{ $mountain->latitude }}</p>
                                                                    <p class="mb-0">Long: {{ $mountain->longitude }}</p>
                                                                `)
                mountainMarker.setPopup(mountainPopup)
                mountainPopup.addTo(map)
            }

            let peakLongitude = "{{ $peak->longitude }}"
            let peakLatitude = "{{ $peak->latitude }}"
            if (peakLongitude != '' && peakLatitude != '') {
                const peakMarker = new mapboxgl.Marker({ color: 'purple', scale: 1 })
                    .setLngLat({lng: peakLongitude, lat: peakLatitude})
                    .addTo(map)

                const peakPopup = new mapboxgl.Popup().setHTML(`
                                                                <b>{{ $peak->name }}</b> {{ $peak->height }}
                                                                <p class="mb-0">Lat: {{ $peak->latitude }}</p>
                                                                <p class="mb-0">Long: {{ $peak->longitude }}</p>
                                                            `)
                peakMarker.setPopup(peakPopup)
                peakPopup.addTo(map)
            }
            // end

            $(document).on('click', '.detail', function (e) {
                e.preventDefault()
                let value = $(this).data('value')
                let _this = $(this)
                Swal.fire({
                    title: 'Please click on the maps to create object!',
                    icon: 'info',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        localStorage.setItem('value', value)
                        _this.attr('disabled', true)
                    }
                })
            })

            localStorage.removeItem('value')
            map.on('click', function () {
                let value = localStorage.getItem('value') || ''
                if (value == '') return

                alert(value)
            })
        })
    </script>
@endsection