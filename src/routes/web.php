<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FishController;
use App\Http\Controllers\FishRecordController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\RodController;
use App\Http\Controllers\LeaderBoardController;

Auth::routes();

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

Route::get('/', [HomeController::class, 'index']);
Route::get('/api/user', function (Request $request) {
    return Auth::user();
});

Route::post('/login', [LoginController::class, 'login']);

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::post('/api/logout', function () {
    Auth::logout();
    return response()->json(['success' => true]);
});

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard')->middleware('auth', 'admin');
Route::get('/admin/add-fish', [FishController::class, 'create'])->name('admin.add-fish')->middleware('auth', 'admin');
Route::post('/admin/add-fish', [FishController::class, 'store'])->middleware('auth', 'admin');
Route::get('/admin/edit-fish/{id}', [FishController::class, 'edit'])->name('admin.edit-fish')->middleware('auth', 'admin');
Route::put('/admin/edit-fish/{id}', [FishController::class, 'update'])->middleware('auth', 'admin');
Route::delete('/admin/delete-fish/{id}', [FishController::class, 'destroy'])->name('admin.delete-fish')->middleware('auth', 'admin');

Route::get('/api/fish/random', [FishController::class, 'getRandomFish']);
Route::post('/api/sell-fish', [FishController::class, 'sellFish'])->middleware('auth');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/api/fish/records', [FishRecordController::class, 'index']);
    Route::post('/api/fish/record', [FishRecordController::class, 'store']);
});

// เพิ่ม Route สำหรับหน้า Inventory
Route::get('/inventory', function () {
    return view('inventory'); // ชี้ไปยัง inventory.blade.php
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('rods', RodController::class);
});

Route::get('/api/fish/records', [FishRecordController::class, 'index'])->middleware('auth');

Route::get('/leaderboard', [LeaderBoardController::class, 'index'])->name('leaderboard');
Route::get('/api/leaderboard', [LeaderBoardController::class, 'index'])->name('leaderboard');