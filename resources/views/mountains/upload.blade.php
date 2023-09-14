@extends('layouts.global')
@section('title')
    Upload images for {{ $mountain->name }}
@endsection
@section('content')
    <div class="row pl-3 mb-3">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-white pb-3 d-flex justify-content-between align-items-center">
                    <a href="{{ route('mountains.index') }}" class="btn btn-primary btn-sm"><i class="oi oi-chevron-left"></i> Back</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="{{ $route['storeImages'] }}" method="POST" enctype="multipart/form-data" class="dropzone" id="dropzone">
                                @csrf
                                <div class="dz-default dz-message">
                                    <h4 class="text-muted">Drop files here or click to upload</h4>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        Dropzone.options.dropzone = {
            maxFiles: 5,
            maxFilesSize: 4,
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            addRemoveLinks: true,
            timeout: 50000,
            init: function () {
                var myDropzone = this
                $.ajax({
                    url: "{{ $route['fetchImages'] }}",
                    type: "GET",
                    dataType: 'json',
                    success: function (data) {
                        $.each(data, function (key, value) {
                            let file = {name: value.name, size: value.size}
                            myDropzone.options.addedfile.call(myDropzone, file)
                            myDropzone.options.thumbnail.call(myDropzone, file, value.path)
                            myDropzone.emit('complete', file)
                        })
                    }
                })
            },
            removedfile: function (file) {
                if (this.options.dictRemoveFile) {
                    return Dropzone.confirm("Are you sure to " + this.options.dictRemoveFile, function () {
                        if (file.previewElement.id != "") {
                            let name = file.previewElement.id
                        } else {
                            let name = file.name
                        }
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            type: 'POST',
                            url: "{{ $route['deleteImages'] }}",
                            data: {image: file.name},
                            success: function (data) {
                                alert("File has been successfully removed!")
                            },
                            error: function (e) {
                                alert(e)
                            }
                        })
                        let fileRef
                        return (fileRef = file.previewElement) != null ? fileRef.parentNode.removeChild(file.previewElement) : void 0
                    })
                }
            },
            success: function (file, response) {
                file.previewElement.id = response.success
                let olddatadzname = file.previewElement.querySelector('[data-dz-name]')
                file.previewElement.querySelector('img').alt = response.success
                olddatadzname.innerHTML = response.success
            },
            error: function (file, response) {
                let message = ''
                if ($.type(response) == "string") {
                    message = response
                } else { 
                    message = response.message
                }
                file.previewElement.classList.add('dz-error')
                _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                _results = []

                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i]
                    _results.push(node.textContent = message)                    
                }
                return _results
            }
        }
    </script>
@endsection