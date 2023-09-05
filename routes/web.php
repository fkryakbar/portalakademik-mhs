<?php

use App\Http\Controllers\JadwalPerkuliahanController;
use App\Http\Controllers\KHSController;
use App\Http\Controllers\KRSController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrasiAkademikController;
use App\Http\Controllers\RekapitulasiHasilStudiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->to('/profile');
    }
    return redirect()->to('https://siamad.stitastbr.ac.id');
});


Route::middleware(['auth.mhs'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::post('/profile', [ProfileController::class, 'update']);

    Route::prefix('registrasi-akademik')->group(function () {
        Route::get('/', [RegistrasiAkademikController::class, 'index']);
        Route::post('/', [RegistrasiAkademikController::class, 'upload']);
    });

    Route::middleware(['isRegistered'])->prefix('rekapitulasi-hasil-studi')->group(function () {
        Route::get('/', [RekapitulasiHasilStudiController::class, 'index']);
    });

    Route::middleware(['isRegistered'])->prefix('khs')->group(function () {
        Route::get('/', [KHSController::class, 'index']);
    });

    Route::middleware(['isRegistered'])->prefix('krs')->group(function () {
        Route::get('/', [KRSController::class, 'index']);
    });

    Route::middleware(['isRegistered'])->prefix('jadwal-perkuliahan')->group(function () {
        Route::get('/', [JadwalPerkuliahanController::class, 'index']);
    });


    Route::middleware(['isRegistered'])->prefix('api')->group(function () {
        Route::get('/krs/{kode_tahun_ajaran}', [KRSController::class, 'api_get_krs']);
        Route::get('/khs/{kode_tahun_ajaran}', [KHSController::class, 'api_get_khs']);
    });
});


Route::get('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/');
});


Route::get('/run-dev', function () {
    $user =  User::where('role', 'mahasiswa')->first();
    Auth::login($user);
    return redirect('/');
});
