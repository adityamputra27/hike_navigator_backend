<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\{
    LocationController
};
use App\Http\Controllers\{
    DashboardController,
    MountainController,
    PeakController,
    UserController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Auth::routes();

Route::get('/dashboard', [DashboardController::class, 'index'])->name('home');

Route::post('mountains/datatables/', [MountainController::class, 'datatables'])->name('mountains.datatables');
Route::post('mountains/peakDatatables/', [MountainController::class, 'peakDatatables'])->name('mountains.peakDatatables');
Route::post('mountains/storePeaks', [MountainController::class, 'storePeaks'])->name('mountains.storePeaks');
Route::delete('mountains/destroyPeak/{mountainPeakId}', [MountainController::class, 'destroyPeak'])->name('mountains.destroyPeak');
Route::get('mountains/{mountainId}/peak/{peakId}', [MountainController::class, 'detailPeak'])->name('mountains.detailPeak');
Route::get('mountains/{mountainId}/peak/{peakId}/createTrack/', [MountainController::class, 'createTrack'])->name('mountains.createTrack');
Route::post('mountains/{mountainId}/peak/{peakId}/storeTrack/', [MountainController::class, 'storeTrack'])->name('mountains.storeTrack');
Route::get('mountains/{mountainId}/peak/{peakId}/editTrack/{trackId}', [MountainController::class, 'editTrack'])->name('mountains.editTrack');
Route::patch('mountains/{mountainId}/peak/{peakId}/updateTrack/{trackId}', [MountainController::class, 'updateTrack'])->name('mountains.updateTrack');
Route::post('mountains/trackDatatables/', [MountainController::class, 'trackDatatables'])->name('mountains.trackDatatables');

Route::resource('mountains', MountainController::class);

Route::post('peaks/datatables/', [PeakController::class, 'datatables'])->name('peaks.datatables');
Route::resource('peaks', PeakController::class);

Route::post('users/datatables/', [UserController::class, 'datatables'])->name('users.datatables');
Route::resource('users', UserController::class);

Route::get('provinces/{id}', [LocationController::class, 'getCities'])->name('location.getCities');
