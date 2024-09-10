<?php

use App\Http\Controllers\AkreditasiController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\ProdiLoginController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StandarController;
use App\Http\Controllers\SubstandarController;

Route::get('/', [ProdiLoginController::class, 'index'])->name('home'); // Perbarui route untuk halaman awal

Route::get('/dashboard', function () {
    return view('pages.home.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Route ke master page
    Route::get('/master', function () {
        return view('pages.master.home');
    })->name('master');
    Route::get('/standar', function () {
        return view('pages.master.standar');
    })->name('standar');

    // Akreditasi Routes (CRUD untuk Akreditasi)
    Route::resource('akreditasi', AkreditasiController::class);
    Route::put('/akreditasi/{akreditasi}/activate', [AkreditasiController::class, 'activate'])->name('akreditasi.activate');

    // Standar Routes (CRUD untuk Standar)
    Route::resource('standar', StandarController::class);
    Route::post('/standar/update-order', [StandarController::class, 'updateOrder'])->name('standar.updateOrder');

    // Substandar Routes (CRUD untuk Substandar)
    Route::resource('substandar', SubstandarController::class);
    Route::post('/substandar/update-order', [SubstandarController::class, 'updateOrder'])->name('substandar.updateOrder');

    // Detail Routes (CRUD untuk Detail)
    Route::resource('detail', DetailController::class);
    Route::post('/detail/update-order', [DetailController::class, 'updateOrder'])->name('detail.updateOrder');

    // Route ke berkas page
    Route::get('/berkas', function () {
        return view('pages.berkas.home');
    })->name('berkas');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/login/{prodi}', [ProdiLoginController::class, 'showLoginForm'])->name('prodi.login');
Route::post('/login/{prodi}', [ProdiLoginController::class, 'login']);

require __DIR__ . '/auth.php';
