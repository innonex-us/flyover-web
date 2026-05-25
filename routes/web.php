<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\VisaController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Push Notification Subscriptions
Route::post('/push/subscribe', [\App\Http\Controllers\PushSubscriptionController::class, 'store'])->name('push.subscribe');
Route::post('/push/unsubscribe', [\App\Http\Controllers\PushSubscriptionController::class, 'destroy'])->name('push.unsubscribe');

// Short Link Redirect
Route::get('/s/{code}', [App\Http\Controllers\ShortLinkController::class, 'redirect'])->name('short-link.redirect');

// Short Link API
Route::post('/api/short-link', [App\Http\Controllers\ShortLinkController::class, 'generate'])->name('short-link.generate');

// Pick & Drop Transfers
Route::get('/transfers', [App\Http\Controllers\TransferController::class, 'index'])->name('transfers.index');
Route::post('/transfers', [App\Http\Controllers\TransferController::class, 'store'])->name('transfers.store');
Route::get('/transfers/{booking}/confirmation', [App\Http\Controllers\TransferController::class, 'confirmation'])->name('transfers.confirmation');

// Hotels
Route::get('/hotels', [App\Http\Controllers\HotelController::class, 'index'])->name('hotels.index');
Route::post('/hotels/book', [App\Http\Controllers\HotelController::class, 'book'])->name('hotels.book');
Route::get('/hotels/bookings/{booking}/confirmation', [App\Http\Controllers\HotelController::class, 'confirmation'])->name('hotels.confirmation');
Route::get('/hotels/{hotel:slug}', [App\Http\Controllers\HotelController::class, 'show'])->name('hotels.show');

Route::get('/tours', [PackageController::class, 'index'])->name('packages.index');
Route::get('/plan-my-trip', [PackageController::class, 'showCustomPlanForm'])->name('customize.index');
Route::get('/tours/{slug}', [PackageController::class, 'show'])->name('packages.show');
Route::post('/customize', [PackageController::class, 'customize'])->name('packages.customize.general');
Route::post('/tours/{package}/customize', [PackageController::class, 'customize'])->name('packages.customize');

// Data Export API
Route::prefix('api/export/v1')->middleware('api.token')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\DataExportController::class, 'index']);
    Route::get('/contacts', [App\Http\Controllers\Api\DataExportController::class, 'contacts']);
    Route::get('/inquiries', [App\Http\Controllers\Api\DataExportController::class, 'inquiries']);
    Route::get('/bookings', [App\Http\Controllers\Api\DataExportController::class, 'bookings']);
});

Route::get('/visas', [VisaController::class, 'index'])->name('visas.index');
Route::get('/visas/{slug}', [VisaController::class, 'show'])->name('visas.show');

Route::get('/blog', [App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');

Route::post('/bookings', [App\Http\Controllers\BookingController::class, 'store'])->name('bookings.store');
Route::get('/bookings/{booking}/confirmation', [App\Http\Controllers\BookingController::class, 'confirmation'])->name('bookings.confirmation');

Route::prefix('payments/bkash')->name('payments.bkash.')->group(function () {
    Route::get('/{payment}', [PaymentController::class, 'start'])->name('start');
    Route::get('/success', [PaymentController::class, 'success'])->name('success');
    Route::get('/fail', [PaymentController::class, 'fail'])->name('fail');
    Route::get('/cancel', [PaymentController::class, 'cancel'])->name('cancel');
    // Server-to-server webhook for bKash notifications
    Route::post('/webhook', [PaymentController::class, 'webhook'])
        ->withoutMiddleware([])
        ->middleware([\App\Http\Middleware\VerifyBkashWebhook::class, 'throttle:30,1'])
        ->name('webhook');
});

Route::get('/search/suggestions', [App\Http\Controllers\SearchController::class, 'suggestions'])->name('search.suggestions');

// Analytics API Routes
Route::prefix('api/analytics')->name('analytics.')->group(function () {
    Route::post('/page-view', [App\Http\Controllers\AnalyticsController::class, 'pageView'])->name('page-view');
    Route::post('/event', [App\Http\Controllers\AnalyticsController::class, 'event'])->name('event');
    Route::post('/page-view-update', [App\Http\Controllers\AnalyticsController::class, 'pageViewUpdate'])->name('page-view-update');
    Route::post('/visitor-update', [App\Http\Controllers\AnalyticsController::class, 'visitorUpdate'])->name('visitor-update');
    Route::post('/session-activity', [App\Http\Controllers\AnalyticsController::class, 'sessionActivity'])->name('session-activity');
    Route::post('/consent', [App\Http\Controllers\AnalyticsController::class, 'consent'])->name('consent');
});


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

    // User Notifications
    Route::post('/my/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markRead'])->name('user.notifications.read');
    Route::post('/my/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('user.notifications.read-all');
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
    
    // Analytics
    Route::get('/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/export', [\App\Http\Controllers\Admin\AnalyticsController::class, 'export'])->name('analytics.export');
    
    // New Pages
    Route::get('/analytics-advanced', function () { return view('admin.analytics'); })->name('analytics.advanced');
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/pdf', [\App\Http\Controllers\Admin\ReportController::class, 'pdf'])->name('reports.pdf');
    Route::get('/reports/print', [\App\Http\Controllers\Admin\ReportController::class, 'print'])->name('reports.print');
    Route::get('/users', function () { return view('admin.users'); })->name('users.index');
    Route::get('/settings/general', function () { return view('admin.settings'); })->name('settings.general');
    Route::get('/settings/email', function () { return view('admin.settings'); })->name('settings.email');
    Route::get('/system/logs', function () { return view('admin.system'); })->name('system.logs');
    Route::get('/system/backup', function () { return view('admin.system'); })->name('system.backup');

    Route::delete('/packages/{package}/thumbnail', [\App\Http\Controllers\Admin\PackageController::class, 'removeThumbnail'])->name('packages.thumbnail.destroy');
    Route::delete('/packages/{package}/images/{index}', [\App\Http\Controllers\Admin\PackageController::class, 'removeImage'])->name('packages.images.destroy');
    Route::resource('packages', \App\Http\Controllers\Admin\PackageController::class);
    Route::resource('visas', \App\Http\Controllers\Admin\VisaController::class);
    Route::resource('bookings', \App\Http\Controllers\Admin\BookingController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::resource('customizations', \App\Http\Controllers\Admin\CustomizationController::class)->only(['index', 'show', 'update']);
    Route::post('/contact-messages/mark-all-read', [\App\Http\Controllers\Admin\ContactMessageController::class, 'markAllRead'])->name('contact-messages.markAllRead');
    Route::resource('contact-messages', \App\Http\Controllers\Admin\ContactMessageController::class)->only(['index', 'show', 'destroy']);
    Route::resource('blog', \App\Http\Controllers\Admin\BlogController::class);
    Route::post('/upload-image', [\App\Http\Controllers\Admin\ImageUploadController::class, 'store'])->name('upload.image');

    // Short Links
    Route::resource('short-links', \App\Http\Controllers\Admin\ShortLinkController::class)->only(['index', 'create', 'store', 'destroy']);

    // Pick & Drop
    Route::resource('transfer-routes', \App\Http\Controllers\Admin\TransferRouteController::class);
    Route::resource('transfer-bookings', \App\Http\Controllers\Admin\TransferBookingController::class)->only(['index', 'show', 'update', 'destroy']);

    // Hotels
    Route::resource('hotels', \App\Http\Controllers\Admin\HotelController::class);
    Route::resource('hotels.rooms', \App\Http\Controllers\Admin\HotelRoomController::class);
    Route::resource('hotel-bookings', \App\Http\Controllers\Admin\HotelBookingController::class)->only(['index', 'show', 'update', 'destroy']);

    // Push Notifications (Marketing)
    Route::get('/push-notifications', [\App\Http\Controllers\Admin\PushNotificationController::class, 'index'])->name('push-notifications.index');
    Route::post('/push-notifications/send', [\App\Http\Controllers\Admin\PushNotificationController::class, 'send'])->name('push-notifications.send');

    // In-App Notifications
    Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/lazy', [\App\Http\Controllers\Admin\NotificationController::class, 'lazy'])->name('notifications.lazy');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\Admin\NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('notifications.destroy');
});

require __DIR__.'/auth.php';
