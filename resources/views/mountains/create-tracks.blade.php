@extends('layouts.global')
@section("title") Create Tracks @endsection

@section('content')
<div class="row pl-3 mb-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-white pb-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Choose Location</h5>
                <div>
                    <button class="btn btn-primary" id="saveTrack" data-toggle="modal" data-target="#trackModal" type="button"><i class="oi oi-circle-check"></i> Save Track</button>
                    <input type="hidden" id="geojson">
                    <input type="hidden" id="coordinates">
                    <a href="#" class="btn btn-info"><i class="oi oi-info"></i> Petunjuk Penggunaan</a>
                </div>
            </div>
            <div class="card-body">
                <div id="map" style="width: 100%; height: 500px;"></div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="trackModal" tabindex="-1" aria-labelledby="trackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="trackModalLabel">Confirmation Track</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('mountains.storeTrack', [$mountain->id, $peak->id]) }}" id="trackForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="mountain_peak_id" id="mountain_peak_id" value="{{ $mountainPeak->id }}">
                    <div class="form-group">
                        <label for="">Name : </label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Geojson :</label>
                        <input type="text" name="geojson_modal" readonly id="geojson_modal" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Coordinates :</label>
                        <input type="text" name="coordinates_modal" readonly id="coordinates_modal" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Description <small>(optional)</small></label>
                        <textarea name="description" id="description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        $(function () {
            $('#saveTrack').hide()
            mapboxgl.accessToken = 'pk.eyJ1IjoiaGlrZW5hdmlnYXRvcm5ldyIsImEiOiJjbGxoZXRsdnoxOW5wM2ZwamZ2eTBtMWV1In0.jYkxsonNQIn_GsbJorNkEw';
            let map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/streets-v11',
                zoom: 10,
                center: ["{{ $mountain->longitude }}", "{{ $mountain->latitude }}"],
            });

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

            let startPoint = null
            let endPoint = null
            let route = null
            let pointCount = 0
            let coordinates = []

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

                    coordinates.push(
                        [startPoint.lng, startPoint.lat], [endPoint.lng, endPoint.lat]
                    )
                }

                if (pointCount >= 2) {
                    $('#saveTrack').show()
                    $('#geojson').val(JSON.stringify(route))
                    $('#coordinates').val(JSON.stringify(coordinates))
                }
            })

            $('#trackModal').on('show.bs.modal', function (e) {
                let modal = $(this)
                modal.find('.modal-body #geojson_modal').val($('#geojson').val())
                modal.find('.modal-body #coordinates_modal').val($('#coordinates').val())
            })
        })
    </script>
@endsection