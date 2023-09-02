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