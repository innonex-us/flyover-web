<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\VisaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/tours', [PackageController::class, 'index'])->name('packages.index');
Route::get('/tours/{slug}', [PackageController::class, 'show'])->name('packages.show');
Route::post('/tours/{package}/customize', [PackageController::class, 'customize'])->name('packages.customize');

Route::get('/visas', [VisaController::class, 'index'])->name('visas.index');
Route::get('/visas/{slug}', [VisaController::class, 'show'])->name('visas.show');

Route::post('/bookings', [App\Http\Controllers\BookingController::class, 'store'])->name('bookings.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'admin'])->prefix('cp')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('packages', \App\Http\Controllers\Admin\PackageController::class);
    Route::resource('visas', \App\Http\Controllers\Admin\VisaController::class);
    Route::resource('bookings', \App\Http\Controllers\Admin\BookingController::class)->only(['index', 'show', 'update', 'destroy']);
});

require __DIR__.'/auth.php';
