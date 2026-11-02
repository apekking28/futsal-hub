<?php

use App\Http\Controllers\Admin\BookingManagementController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FieldManagementController;
use App\Http\Controllers\Admin\GorManagementController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CheckBookingController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// -------------------------------------------------------------
// 1. PUBLIC ROUTES (Booking Anonim & Cek Status)
// -------------------------------------------------------------
// ATAU jika sementara halaman beranda langsung menampilkan form booking:
Route::get('/', [BookingController::class, 'index'])->name('booking.index');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/success/{code}', [BookingController::class, 'success'])->name('booking.success');
Route::get('/booking/slots', [BookingController::class, 'getSlots'])->name('booking.slots');

Route::get('/cek-booking', [CheckBookingController::class, 'index'])->name('booking.check');
Route::post('/cek-booking/{id}/upload', [CheckBookingController::class, 'uploadProof'])->name('booking.upload_proof');

// -------------------------------------------------------------
// 2. AUTHENTICATION ROUTES (Login & Logout Staff)
// -------------------------------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// -------------------------------------------------------------
// 3. ADMIN & KASIR ROUTES (Harus Login)
// -------------------------------------------------------------
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard (Super Admin & Admin GOR)
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['role:super-admin|admin-gor'])
        ->name('dashboard');

    // Redirection Route
    Route::get('/', function () {
        $user = Auth::user();
        if ($user && $user->hasRole('kasir')) {
            return redirect()->route('admin.bookings.index');
        }

        return redirect()->route('admin.dashboard');
    });

    // Manajemen Booking & Check-In (Super Admin, Admin GOR, Kasir)
    Route::get('/bookings', [BookingManagementController::class, 'index'])->name('bookings.index');
    Route::post('/bookings/check-in', [BookingManagementController::class, 'checkIn'])->name('bookings.checkin');

    // Fitur Super Admin & Admin GOR
    Route::middleware(['role:super-admin|admin-gor'])->group(function () {
        Route::patch('/bookings/{id}/approve', [BookingManagementController::class, 'approvePayment'])->name('bookings.approve');
        Route::patch('/bookings/{id}/reject', [BookingManagementController::class, 'rejectPayment'])->name('bookings.reject');
        Route::patch('/bookings/{id}/cancel', [BookingManagementController::class, 'cancelBooking'])->name('bookings.cancel');

        // Re-Update Status (Fitur Koreksi Kesalahan)
        Route::patch('/bookings/{id}/update-status', [BookingManagementController::class, 'updateStatus'])->name('bookings.update_status');

        // CRUD Manajemen Lapangan
        Route::get('/fields', [FieldManagementController::class, 'index'])->name('fields.index');
        Route::post('/fields', [FieldManagementController::class, 'store'])->name('fields.store');
        Route::put('/fields/{id}', [FieldManagementController::class, 'update'])->name('fields.update');
        Route::delete('/fields/{id}', [FieldManagementController::class, 'destroy'])->name('fields.destroy');
    });

    // CRUD Manajemen GOR (Khusus Super Admin)
    // CRUD Manajemen User & RBAC (Khusus Super Admin)
    Route::middleware(['role:super-admin'])->group(function () {
        Route::get('/gors', [GorManagementController::class, 'index'])->name('gors.index');
        Route::post('/gors', [GorManagementController::class, 'store'])->name('gors.store');
        Route::put('/gors/{id}', [GorManagementController::class, 'update'])->name('gors.update');
        Route::delete('/gors/{id}', [GorManagementController::class, 'destroy'])->name('gors.destroy');

        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    });

});
