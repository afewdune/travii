<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FishRecordController;
use App\Http\Controllers\FishController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SelectedRodController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned to the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum', 'throttle:1000,1'])->post('/fish/record', [FishRecordController::class, 'store']);
Route::middleware(['auth:sanctum', 'throttle:1000,1'])->get('/fish/records', [FishRecordController::class, 'index']);

Route::middleware(['auth:sanctum', 'throttle:1000,1'])->get('/admin', [AdminController::class, 'index']);
Route::middleware(['auth:sanctum', 'throttle:1000,1'])->get('/fish', [FishController::class, 'index']);

Route::get('/api/rods', [RodController::class, 'index']);
Route::middleware('auth:sanctum')->get('/selected-rod', [SelectedRodController::class, 'getSelectedRod']);
Route::middleware('auth:sanctum')->get('/user-rods', [SelectedRodController::class, 'getUserRods']);