<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisSuratController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
    return redirect('/dashboard');
});

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('/dashboard')->middleware('auth')->group(function () {
    /* Dashboard */
    Route::get('/', [DashboardController::class, 'index']);
    Route::middleware(['role:admin'])->group(function () {
        /* User */
        Route::controller(UserController::class)->group(function () {
            Route::get('/user', 'index');
            Route::post('/user/tambah', 'store');
            Route::post('/user/{id}/edit', 'update')->where('id', '[0-9+]');
            Route::delete('/user/{id}/delete', 'delete')->where('id', '[0-9]+');
        });

        /* Jenis Surat */
        Route::controller(JenisSuratController::class)->group(function () {
            Route::get('/surat/jenis', 'index');
            Route::post('/surat/jenis/tambah', 'store');
            Route::post('/surat/jenis/{id}/edit', 'store');
            Route::delete('/surat/jenis/{id}/delete', 'delete');
        });

    });

    /* Surat */
    Route::controller(SuratController::class)->group(function () {
        Route::get('/surat', 'index');
        Route::get('/surat', 'index');
        Route::post('/surat', 'store');
        Route::get('/surat/download', 'download');
        Route::post('/surat/{id}', 'update');
        Route::delete('/surat/{id}', 'delete');
    });

    /* Log */
    Route::controller(LogController::class)->group(function () {
        Route::get('/log', 'index');
    });
});
