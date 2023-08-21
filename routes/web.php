<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController
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
