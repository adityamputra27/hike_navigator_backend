<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    Setting
};

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        return view('settings.form', [
            'setting' => $setting,
        ]);
    }

    public function update(Request $request) 
    {
        $setting = Setting::first();
        $setting->name = $request->name;
        $setting->version = $request->version;
        $setting->android_link = $request->android_link;
        $setting->ios_link = $request->ios_link;
        $setting->android_package = $request->android_package;
        $setting->ios_package = $request->ios_package;
        $setting->address = $request->address;
        
        if ($setting->save()) {
            return redirect()->back()->with('status', 'Success change mobile app setting!');
        }
    }
}
