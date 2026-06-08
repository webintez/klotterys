<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminResultController;
use App\Http\Controllers\Admin\AdminSettingController;

// Frontend routes
Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/buy-tickets', function () {
    return view('lottery-ticket');
})->name('buy-tickets');

// Booking routes
Route::get('/book-ticket', [BookingController::class, 'book'])->name('book-ticket');
Route::post('/book-ticket/submit', [BookingController::class, 'store'])->name('book-ticket.submit');
Route::post('/book-ticket/pay-success', [BookingController::class, 'paySuccess'])->name('book-ticket.pay-success');

// Tracking routes
Route::get('/track-order', [BookingController::class, 'track'])->name('track-order');
Route::post('/track-order/search', [BookingController::class, 'search'])->name('track-order.search');

// Results routes
Route::get('/results', [ResultController::class, 'index'])->name('results');
Route::get('/results/winner', [ResultController::class, 'winner'])->name('results.winner');
Route::get('/results/winner/certificate-image', [ResultController::class, 'certificateImage'])->name('results.winner.certificate-image');
Route::post('/results/check', [ResultController::class, 'check'])->name('results.check');
Route::post('/results/claim', [ResultController::class, 'claim'])->name('results.claim');

// Admin routes
Route::prefix('admin')->group(function () {
    // Guest Admin Routes
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    });

    // Authenticated Admin Routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        
        // Bookings
        Route::get('/bookings', [AdminBookingController::class, 'index'])->name('admin.bookings.index');
        Route::get('/bookings/{id}/edit', [AdminBookingController::class, 'edit'])->name('admin.bookings.edit');
        Route::put('/bookings/{id}', [AdminBookingController::class, 'update'])->name('admin.bookings.update');
        Route::delete('/bookings/{id}', [AdminBookingController::class, 'destroy'])->name('admin.bookings.destroy');

        // Draw Results
        Route::get('/results', [AdminResultController::class, 'index'])->name('admin.results.index');
        Route::post('/results', [AdminResultController::class, 'store'])->name('admin.results.store');
        Route::put('/results/{id}', [AdminResultController::class, 'update'])->name('admin.results.update');
        Route::delete('/results/{id}', [AdminResultController::class, 'destroy'])->name('admin.results.destroy');

        // Website Settings
        Route::get('/settings', [AdminSettingController::class, 'edit'])->name('admin.settings.edit');
        Route::post('/settings', [AdminSettingController::class, 'update'])->name('admin.settings.update');

        // Logout
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });
});
