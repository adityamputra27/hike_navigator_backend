<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\{
    AuthController,
    MountainController,
    ClimbingPlanController,
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware('auth.api')->group(function () {
    Route::get('mountains', [MountainController::class, 'index']);

    Route::post('climbing-plans/create/', [ClimbingPlanController::class, 'create']);
    Route::get('climbing-plans/getActiveUser/{userId}/', [ClimbingPlanController::class, 'getActiveUser']);
    Route::get('climbing-plans/getSavedUser/{userId}/', [ClimbingPlanController::class, 'getSavedUser']);
});
