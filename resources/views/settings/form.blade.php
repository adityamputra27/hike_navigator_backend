@extends('layouts.global')
@section("title") Mobile App Settings @endsection

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
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-white pb-3">
                <h5 class="mb-0">Form</h5>
            </div>
            <form action="{{ route('setting.update') }}" id="" method="POST">
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong><i class="oi oi-info"></i> Perhatian!</strong><br>
                        Jika <b>app version</b> diubah maka akan muncul notifikasi update di mobile
                    </div>
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="id" value="{{ !empty($setting) ? $setting->id : 0 }}">
                    <div class="form-group">
                        <label for="">App Name :</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ !empty($setting) ? $setting->name : '' }}" required>
                    </div>
                    <div class="form-group">
                        <label for="">App Version : </label>
                        <input type="text" name="version" id="version" class="form-control" value="{{ !empty($setting) ? $setting->version : '' }}" required>
                    </div>
                    <div class="form-group">
                        <label for="">Google Play Store (Link) : </label>
                        <input type="text" name="android_link" id="android_link" class="form-control" value="{{ !empty($setting) ? $setting->android_link : '' }}" required>
                    </div>
                    <div class="form-group">
                        <label for="">App Store (Link) : </label>
                        <input type="text" name="ioslink" id="ioslink" class="form-control" value="{{ !empty($setting) ? $setting->ios_link : '' }}" required>
                    </div>
                    <div class="form-group">
                        <label for="">Android Package Name : </label>
                        <input type="text" name="android_package" id="android_package" class="form-control" value="{{ !empty($setting) ? $setting->android_package : '' }}" required>
                    </div>
                    <div class="form-group">
                        <label for="">IOS Package Name : </label>
                        <input type="text" name="ios_package" id="ios_package" class="form-control" value="{{ !empty($setting) ? $setting->ios_package : '' }}" required>
                    </div>
                    <div class="form-group">
                        <label for="">Address</label>
                        <textarea name="address" id="address" class="form-control">{{ !empty($setting) ? $setting->address : '' }}</textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-lg">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection