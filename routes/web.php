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

Route::get('/blog', [App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');

Route::post('/bookings', [App\Http\Controllers\BookingController::class, 'store'])->name('bookings.store')->middleware('throttle:booking_submission');
Route::get('/bookings/{booking}/confirmation', [App\Http\Controllers\BookingController::class, 'confirmation'])->name('bookings.confirmation');

// Static Pages
Route::view('/about', 'pages.about')->name('about');
Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');
Route::view('/privacy', 'pages.privacy')->name('privacy');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'two-factor'])->name('dashboard');

Route::middleware(['auth', 'two-factor'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 2FA Routes
    Route::get('/2fa/setup', [App\Http\Controllers\TwoFactorController::class, 'enable'])->name('two-factor.enable');
    Route::post('/2fa/confirm', [App\Http\Controllers\TwoFactorController::class, 'store'])->name('two-factor.confirm');
    Route::delete('/2fa/disable', [App\Http\Controllers\TwoFactorController::class, 'destroy'])->name('two-factor.disable');
});

Route::get('/2fa/challenge', [App\Http\Controllers\TwoFactorController::class, 'index'])->name('2fa.index')->middleware('auth');
Route::post('/2fa/challenge', [App\Http\Controllers\TwoFactorController::class, 'verify'])->name('2fa.verify')->middleware('auth');

Route::middleware(['auth', 'verified', 'admin', 'two-factor'])->prefix('cp')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');

    Route::resource('packages', \App\Http\Controllers\Admin\PackageController::class);
    Route::resource('visas', \App\Http\Controllers\Admin\VisaController::class);
    Route::resource('bookings', \App\Http\Controllers\Admin\BookingController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::resource('customizations', \App\Http\Controllers\Admin\CustomizationController::class)->only(['index', 'update']);
    Route::resource('contact-messages', \App\Http\Controllers\Admin\ContactMessageController::class)->only(['index', 'show', 'destroy']);
    Route::resource('blog', \App\Http\Controllers\Admin\BlogController::class);
});

require __DIR__.'/auth.php';
