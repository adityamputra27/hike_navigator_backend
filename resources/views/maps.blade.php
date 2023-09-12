<!-- Modal -->
<div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mapModalLabel">Choose Location <small>(Click on the map to get your coordinates)</small></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="map" style="width: 100%; height: 450px; position: relative;"></div>
                <h5 class="modal-title mt-3 mb-2">Current Position</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Latitude :</label>
                            <input type="text" class="form-control" readonly id="latitude_modal">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Longitude :</label>
                            <input type="text" class="form-control" readonly id="longitude_modal">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Selesai</button>
            </div>
        </div>
    </div>
</div>
@push('map-scripts')
<script>
    $(function () {
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
@endpush