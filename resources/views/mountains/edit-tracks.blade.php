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
                    <a href="{{ route('mountains.detailPeak', [$mountain->id, $peak->id]) }}" class="btn btn-danger"><i class="oi oi-chevron-left"></i> Back</a>
                    <button type="button" class="btn btn-secondary cancel"><i class="oi oi-reload"></i> Cancel</button>
                    <button type="button" class="btn btn-danger detail" data-value="marks">Create Mark</button>
                    <button type="button" class="btn btn-info detail" data-value="cross_roads">Create Cross Road</button>
                    <button type="button" class="btn btn-warning detail" data-value="posts">Create Post</button>
                    <button type="button" class="btn btn-success detail" data-value="rivers">Create River</button>
                    <button type="button" class="btn btn-primary detail" data-value="waterfalls">Create Waterfall</button>
                    <button type="button" class="btn btn-info detail" data-value="water_springs">Create Water Spring</button>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong><i class="oi oi-info"></i> Perhatian!</strong><br>
                    Harap klik tombol cancel jika ingin batal edit ketika salah satu <b>tombol disabled!</b>
                </div>
                <div id="map" style="width: 100%; height: 500px;"></div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Confirmation Location</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('mountains.updateTrack', [$mountain->id, $peak->id, $track->id]) }}" id="trackForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <input type="hidden" name="mountain_peak_id" id="mountain_peak_id" value="{{ $mountainPeak->id }}">
                    <input type="hidden" name="track_id" id="track_id" value="{{ $track->id }}">
                    <div class="form-group">
                        <label for="">Code :</label>
                        <input type="text" name="value" id="value" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="">Title : </label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Latitude :</label>
                                <input type="text" name="latitude" id="latitude"  readonly class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Longitude :</label>
                                <input type="text" name="longitude" id="longitude"  readonly class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group contact_number_form">
                        <label for="">Contact Number :</label>
                        <input type="text" id="contact_number" name="contact_number" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Description <small>(optional)</small></label>
                        <textarea name="description" id="description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Save</button>
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

            // get all track detail
            let tracks = JSON.parse(decodeURIComponent("{{ rawurlencode($track) }}"))
            
            for (const waterfall of tracks.waterfalls) {
                const el = document.createElement('div')
                el.className = 'waterfall_markers';

                new mapboxgl.Marker(el).setLngLat({lng: waterfall.longitude, lat: waterfall.latitude}).addTo(map)
            }
            for (const river of tracks.rivers) {
                const el = document.createElement('div')
                el.className = 'river_markers';

                new mapboxgl.Marker(el).setLngLat({lng: river.longitude, lat: river.latitude}).addTo(map)
            }
            for (const waterspring of tracks.watersprings) {
                const el = document.createElement('div')
                el.className = 'waterspring_markers';

                new mapboxgl.Marker(el).setLngLat({lng: waterspring.longitude, lat: waterspring.latitude}).addTo(map)
            }
            for (const post of tracks.posts) {
                const el = document.createElement('div')
                el.className = 'post_markers';

                new mapboxgl.Marker(el).setLngLat({lng: post.longitude, lat: post.latitude}).addTo(map)
            }
            for (const mark of tracks.marks) {
                const el = document.createElement('div')
                el.className = 'mark_markers';

                new mapboxgl.Marker(el).setLngLat({lng: mark.longitude, lat: mark.latitude}).addTo(map)
            }
            for (const cross_road of tracks.cross_roads) {
                const el = document.createElement('div')
                el.className = 'cross_road_markers';

                new mapboxgl.Marker(el).setLngLat({lng: cross_road.longitude, lat: cross_road.latitude}).addTo(map)
            }

            // set marks

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
            $('.contact_number_form').hide()
            map.on('click', function (e) {
                let value = localStorage.getItem('value') || ''
                if (value == '') return
                $('#detailModal').modal('show')
                $('#value').val(value.toUpperCase())
                $('#latitude').val(e.lngLat.lat)
                $('#longitude').val(e.lngLat.lng)

                if (value == 'posts') {
                    $('.contact_number_form').show()
                }
            })

            $('.cancel').on('click', function () {
                localStorage.removeItem('value')
                location.reload()
            })
        })
    </script>
@endsection