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
                    <input type="hidden" name="start_latitude" id="start_latitude">
                    <input type="hidden" name="start_longitude" id="start_longitude">
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong><i class="oi oi-info"></i> Perhatian!</strong><br>
                    Setiap rute akhir ketika membuat track akan menjadi <b>start point user!</b>
                </div>
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
                        <label for="">Hiking Time : </label>
                        <input type="number" name="time" id="time" class="form-control" placeholder="0" required>
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
                        <label for="">Start Point :</label>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="start_latitude_modal" readonly id="start_latitude_modal" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="start_longitude_modal" readonly id="start_longitude_modal" class="form-control">
                            </div>
                        </div>
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
            mapboxgl.accessToken = 'pk.eyJ1IjoiaGlrZW5hdmlnYXRvcm5ldyIsImEiOiJjbGxoZXRsdnoxOW5wM2ZwamZ2eTBtMWV1In0.jYkxsonNQIn_GsbJorNkEw';
            let map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/streets-v11',
                zoom: 13,
                center: ["{{ $mountain->longitude }}", "{{ $mountain->latitude }}"],
            });

            const coordinatesGeocoder = function (query) {
                const matches = query.match(/^[ ]*(?:Lat: )?(-?\d+\.?\d*)[, ]+(?:Lng: )?(-?\d+\.?\d*)[ ]*$/i)
                if (!matches) {
                    return null
                }
                function coordinateFeature(lng, lat) {
                    return {
                        center: [lng, lat],
                        geometry: {
                            type: 'Point',
                            coordinates: [lng, lat]
                        },
                        place_name: 'Lat: ' + lat + ' Lng: ' + lng,
                        place_type: ['coordinate'],
                        properties: {},
                        type: 'Feature'
                    };
                }
                    
                const coord1 = Number(matches[1]);
                const coord2 = Number(matches[2]);
                const geocodes = [];
                    
                if (coord1 < -90 || coord1 > 90) {
                    geocodes.push(coordinateFeature(coord1, coord2));
                }
                    
                if (coord2 < -90 || coord2 > 90) {
                    geocodes.push(coordinateFeature(coord2, coord1));
                }
                
                if (geocodes.length === 0) {
                    geocodes.push(coordinateFeature(coord1, coord2));
                    geocodes.push(coordinateFeature(coord2, coord1));
                }
                
                return geocodes;
            }

            let geocoder = new MapboxGeocoder({
                accessToken: mapboxgl.accessToken,
                mapboxgl: mapboxgl,
                marker: {
                    color: 'orange'
                },
                placeholder: 'Cari lokasi atau (lat,long)',
                reverseGeocode: true,
                localGeocoder: coordinatesGeocoder
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
                const el = document.createElement('div')
                el.className = 'mountain_markers';
                const mountainMarker =  new mapboxgl.Marker(el).setLngLat({lng: mountainLongitude, lat: mountainLatitude}).addTo(map)
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
                const el = document.createElement('div')
                el.className = 'mountain_markers';
                const peakMarker =  new mapboxgl.Marker(el).setLngLat({lng: peakLongitude, lat: peakLatitude}).addTo(map)

                const peakPopup = new mapboxgl.Popup().setHTML(`
                                                                <b>{{ $peak->name }}</b> {{ $peak->height }}
                                                                <p class="mb-0">Lat: {{ $peak->latitude }}</p>
                                                                <p class="mb-0">Long: {{ $peak->longitude }}</p>
                                                            `)
                peakMarker.setPopup(peakPopup)
                peakPopup.addTo(map)
            }
            // end

            const draw = new MapboxDraw({
                displayControlsDefault: false,
                controls: {
                    trash: true,
                    polygon: false,
                    line_string: true,
                    point: true,
                },
                modes: Object.assign({}, MapboxDraw.modes),
                defaultMode: 'draw_polygon',
                styles: [
                    {
                        'id': 'gl-draw-line',
                        'type': 'line',
                        'filter': ['all', ['==', '$type', 'LineString'], ['!=', 'mode', 'static']],
                        'layout': {
                            'line-cap': 'round',
                            'line-join': 'round'
                        },
                        'paint': {
                            'line-color': '#FFF', // Warna garis putih
                            'line-dasharray': [2, 2],
                            'line-width': 2
                        }
                    },
                    {
                        'id': 'gl-draw-line-static',
                        'type': 'line',
                        'filter': ['all', ['==', '$type', 'LineString'], ['==', 'mode', 'static']],
                        'layout': {
                            'line-cap': 'round',
                            'line-join': 'round'
                        },
                        'paint': {
                            'line-color': '#FFF', // Warna garis putih
                            'line-width': 3
                        }
                    },
                    {
                        'id': 'gl-draw-polygon-and-line-vertex-inactive',
                        'type': 'circle',
                        'filter': ['all', ['==', 'meta', 'vertex'], ['!=', 'active', 'true']],
                        'paint': {
                            'circle-radius': 5,
                            'circle-color': '#FFF' // Warna titik putih
                        }
                    },
                    {
                        'id': 'gl-draw-polygon-and-line-vertex-active',
                        'type': 'circle',
                        'filter': ['all', ['==', 'meta', 'vertex'], ['==', 'active', 'true']],
                        'paint': {
                            'circle-radius': 7,
                            'circle-color': '#000' // Warna titik putih
                        }
                    }
                ]
            });

            map.addControl(draw, 'top-left');
            draw.changeMode('draw_line_string');

            let startPoint = null
            let endPoint = null
            let pointCount = 0
            let mapClickable = false

            $('#saveTrack').hide()
            map.on('click', function (e) {
                if (!startPoint) {
                    startPoint = e.lngLat
                    pointCount++
                }

                if (pointCount > 0) {
                    $('#saveTrack').show()
                    $('#start_latitude').val(startPoint.lat)
                    $('#start_longitude').val(startPoint.lng)
                }
            })

            $('#trackModal').on('show.bs.modal', function (e) {
                let modal = $(this)
                let data = draw.getAll()
                let coordinatesData = data.features[0].geometry.coordinates
                let geojsonString = JSON.stringify(data)
                let coordinatesString = JSON.stringify(coordinatesData)

                modal.find('.modal-body #geojson_modal').val(geojsonString)
                modal.find('.modal-body #coordinates_modal').val(coordinatesString)
                modal.find('.modal-body #start_latitude_modal').val($('#start_latitude').val())
                modal.find('.modal-body #start_longitude_modal').val($('#start_longitude').val())
            })
        })
    </script>
@endsection