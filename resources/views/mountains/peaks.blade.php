<!-- Modal -->
<div class="modal fade" id="peaksModal" tabindex="-1" aria-labelledby="peaksModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="peaksModalLabel">Select Peaks</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 align-self-center">
                        <div class="form-group">
                            <select name="mountain_peak_id" id="mountain_peak_id" style="width: 100%;" class="form-control form-control-lg"></select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit"><i class="io io-plus"></i> Simpan</button>
                        </div>
                    </div>
                </div>
                <table class="table table-hovered table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Status</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>