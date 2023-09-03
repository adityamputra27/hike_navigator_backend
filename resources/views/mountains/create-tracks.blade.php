@extends('layouts.global')
@section("title") Create Tracks @endsection

@section('content')
<div class="row pl-3 mb-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-white pb-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Choose Location</h5>
                    {{-- set for tracks --}}
                <div>
                    <button class="btn btn-primary" id="saveTrack" type="button"><i class="oi oi-circle-check"></i> Save Track</button>
                    <a href="#" class="btn btn-info"><i class="oi oi-info"></i> Petunjuk Penggunaan</a>
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
            $('#saveTrack').hide()
            mapboxgl.accessToken = 'pk.eyJ1IjoiaGlrZW5hdmlnYXRvcm5ldyIsImEiOiJjbGxoZXRsdnoxOW5wM2ZwamZ2eTBtMWV1In0.jYkxsonNQIn_GsbJorNkEw';
            var map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/streets-v11',
                zoom: 10,
                center: [-74.006, 40.7128],
            });

            var geocoder = new MapboxGeocoder({
                accessToken: mapboxgl.accessToken,
                mapboxgl: mapboxgl,
                marker: {
                    color: 'orange'
                },
                placeholder: 'Cari lokasi...'
            });

            navigator.geolocation.getCurrentPosition(function (position) {
                let userLocation = [position.coords.longitude, position.coords.latitude]
                map.on('load', function () {
                    map.setCenter(userLocation);
                });
                
                $('#longitude_modal').val(userLocation[0])
                $('#latitude_modal').val(userLocation[1])
            })

            map.addControl(geocoder);

            geocoder.on('result', function (e) {
                var searchResult = e.result;
                var userLocation = searchResult.geometry.coordinates;
                map.setCenter(userLocation);

                $('#longitude_modal').val(userLocation[0]);
                $('#latitude_modal').val(userLocation[1]);
            });

            navigator.geolocation.getCurrentPosition(function (position) {
                var userLocation = [position.coords.longitude, position.coords.latitude];
                map.setCenter(userLocation);
                map.setZoom(10);
            });

            let startPoint = null
            let endPoint = null
            let route = null
            let pointCount = 0

            map.on('click', function (e) {
                if (!startPoint) {
                    startPoint = e.lngLat
                    new mapboxgl.Marker({ color: 'red', scale: 0.75 })
                        .setLngLat(startPoint)
                        .addTo(map)
                    pointCount++
                } else {
                    endPoint = e.lngLat
                    new mapboxgl.Marker({ color: 'blue', scale: 0.75 })
                        .setLngLat(endPoint)
                        .addTo(map)
                    pointCount++

                    if (route == null) {
                        route = {
                            type: 'FeatureCollection',
                            features: [],
                        }
                        map.addSource('route', {
                            type: 'geojson',
                            data: route
                        })
                        map.addLayer({
                            id: 'route',
                            type: 'line',
                            source: 'route',
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
                    }
                    
                    route.features.push({
                        type: 'Feature',
                        properties: {},
                        geometry: {
                            type: 'LineString',
                            coordinates: [[startPoint.lng, startPoint.lat], [endPoint.lng, endPoint.lat]],
                        },
                    },)

                    map.getSource('route').setData(route)
                    startPoint = endPoint
                }

                if (pointCount >= 2) {
                    $('#saveTrack').show()
                }
            })
        })
    </script>
@endsection