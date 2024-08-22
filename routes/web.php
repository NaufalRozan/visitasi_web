<?php

use App\Http\Controllers\ProdiLoginController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StandarController;

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

    // Standar Filter
    Route::get('/standar', [StandarController::class, 'index'])->name('standar.index');
    Route::get('/standar/filter', [StandarController::class, 'filter'])->name('standar.filter');
    Route::post('/standar', [StandarController::class, 'store'])->name('standar.store');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/login/{prodi}', [ProdiLoginController::class, 'showLoginForm'])->name('prodi.login');
Route::post('/login/{prodi}', [ProdiLoginController::class, 'login']);

require __DIR__ . '/auth.php';
