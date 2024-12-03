<?php

use App\Http\Controllers\AkreditasiController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\DetailItemController;
use App\Http\Controllers\ProdiLoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\StandarController;
use App\Http\Controllers\SubstandarController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route ke halaman login
Route::get('/', [ProdiLoginController::class, 'index'])->name('home');

// Route dashboard yang hanya bisa diakses jika user telah login
Route::get('/dashboard', function () {
    return view('pages.home.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Middleware auth untuk memastikan user sudah login sebelum mengakses route berikut
Route::middleware('auth')->group(function () {

    // Route ke master page
    Route::get('/master', function () {
        return view('pages.master.home');
    })->name('master');

    // Route ke halaman Standar (Semua role bisa mengakses halaman ini)
    Route::resource('standar', StandarController::class);
    Route::post('/standar/update-order', [StandarController::class, 'updateOrder'])->name('standar.updateOrder');

    // Route ke berkas page
    Route::get('/berkas', function () {
        return view('pages.berkas.home');
    })->name('berkas');

    // Route ke resume page
    Route::get('/resume', function () {
        return view('pages.resume.home');
    })->name('resume');

    // Akreditasi Routes (CRUD untuk Akreditasi)
    Route::resource('akreditasi', AkreditasiController::class);
    Route::put('/akreditasi/{akreditasi}/activate', [AkreditasiController::class, 'activate'])->name('akreditasi.activate');

    // Substandar Routes (CRUD untuk Substandar)
    Route::resource('substandar', SubstandarController::class);
    Route::post('/substandar/update-order', [SubstandarController::class, 'updateOrder'])->name('substandar.updateOrder');

    // Detail Routes (CRUD untuk Detail)
    Route::resource('detail', DetailController::class);
    Route::post('/detail/update-order', [DetailController::class, 'updateOrder'])->name('detail.updateOrder');
    Route::get('/berkas/detail/{substandar_id}', [DetailController::class, 'showDetails'])->name('detail.show');

    // Detail Item Routes (CRUD untuk Detail Item)
    Route::resource('detail_item', DetailItemController::class);
    Route::resource('detailitem', DetailItemController::class)->only(['store', 'destroy']);
    Route::post('/detail_item', [DetailItemController::class, 'store'])->name('detail_item.store');
    Route::get('/detail_item/download/{id}', [DetailItemController::class, 'download'])->name('detail_item.download');
    Route::post('/detail_item/update-order', [DetailItemController::class, 'updateOrder'])->name('detail_item.updateOrder');
    Route::get('/detail/{substandar_id}', [DetailItemController::class, 'showDetails'])->name('detail.showDetails');
    Route::get('detail_item/{id}/view', [DetailItemController::class, 'view'])->name('detail_item.view');


    // Resume Routes
    Route::resource('resume', ResumeController::class);

    // Route untuk profile user
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route login Prodi
Route::get('/login/{prodi}', [ProdiLoginController::class, 'showLoginForm'])->name('prodi.login');
Route::post('/login/{prodi}', [ProdiLoginController::class, 'login']);

// Route untuk user yang hanya bisa diakses oleh role Universitas
Route::get('/user', [UserController::class, 'index'])->name('user')->middleware(['auth', 'admin']);

// Middleware khusus untuk admin (role Universitas saja)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('user', UserController::class);
    Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/user', [UserController::class, 'store'])->name('user.store');
    Route::get('user/{user}/get', [UserController::class, 'getUser'])->name('user.get');
});

// Autentikasi route (login, register, reset password, dll.)
require __DIR__ . '/auth.php';
