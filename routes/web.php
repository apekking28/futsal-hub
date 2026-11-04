<?php

use App\Http\Controllers\Admin\BookingManagementController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DistrictManagementController;
use App\Http\Controllers\Admin\FieldManagementController;
use App\Http\Controllers\Admin\GorManagementController;
use App\Http\Controllers\Admin\PermissionManagementController;
use App\Http\Controllers\Admin\RoleManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CheckBookingController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - FutsalHub Platform
|--------------------------------------------------------------------------
*/

// =========================================================================
// 1. PUBLIC ROUTES (Pemesanan Anonim, Slot Realtime & Cek Status Booking)
// =========================================================================
Route::get('/', [BookingController::class, 'index'])->name('booking.index');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/success/{code}', [BookingController::class, 'success'])->name('booking.success');
Route::get('/booking/slots', [BookingController::class, 'getSlots'])->name('booking.slots');

Route::get('/cek-booking', [CheckBookingController::class, 'index'])->name('booking.check');
Route::post('/cek-booking/{id}/upload', [CheckBookingController::class, 'uploadProof'])->name('booking.upload_proof');

// =========================================================================
// 2. AUTHENTICATION ROUTES (Login & Logout Staff / Admin)
// =========================================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// =========================================================================
// 3. ADMIN & KASIR ROUTES (Perlu Autentikasi Staff / Pengelola GOR)
// =========================================================================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // ---------------------------------------------------------------------
    // Redirection Handler (Kasir -> Bookings, Super Admin/Admin GOR -> Dashboard)
    // ---------------------------------------------------------------------
    Route::get('/', function () {
        /** @var User|null $user */
        $user = Auth::user();
        if ($user && $user->hasRole('kasir')) {
            return redirect()->route('admin.bookings.index');
        }

        return redirect()->route('admin.dashboard');
    });

    // ---------------------------------------------------------------------
    // Dashboard (Super Admin & Admin GOR)
    // ---------------------------------------------------------------------
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['role:super-admin|admin-gor'])
        ->name('dashboard');

    // ---------------------------------------------------------------------
    // Manajemen Booking & Redemption Check-In (Super Admin, Admin GOR & Kasir)
    // ---------------------------------------------------------------------
    Route::get('/bookings', [BookingManagementController::class, 'index'])->name('bookings.index');
    Route::post('/bookings/check-in', [BookingManagementController::class, 'checkIn'])->name('bookings.checkin');

    // ---------------------------------------------------------------------
    // Akses Manajemen Tambahan (Super Admin & Admin GOR)
    // ---------------------------------------------------------------------
    Route::middleware(['role:super-admin|admin-gor'])->group(function () {
        // Approval & Pembatalan Transaksi
        Route::patch('/bookings/{id}/approve', [BookingManagementController::class, 'approvePayment'])->name('bookings.approve');
        Route::patch('/bookings/{id}/reject', [BookingManagementController::class, 'rejectPayment'])->name('bookings.reject');
        Route::patch('/bookings/{id}/cancel', [BookingManagementController::class, 'cancelBooking'])->name('bookings.cancel');

        // Re-Update / Koreksi Status Transaksi
        Route::patch('/bookings/{id}/update-status', [BookingManagementController::class, 'updateStatus'])->name('bookings.update_status');

        // CRUD Manajemen Lapangan
        Route::get('/fields', [FieldManagementController::class, 'index'])->name('fields.index');
        Route::post('/fields', [FieldManagementController::class, 'store'])->name('fields.store');
        Route::put('/fields/{id}', [FieldManagementController::class, 'update'])->name('fields.update');
        Route::delete('/fields/{id}', [FieldManagementController::class, 'destroy'])->name('fields.destroy');

        // Pengelolaan Foto/Galeri Lapangan (Support WebP & Polymorphic Image)
        Route::post('/fields/{field}/images/{image}/set-cover', [FieldManagementController::class, 'setCover'])->name('fields.images.set-cover');
        Route::delete('/images/{image}', [FieldManagementController::class, 'deleteImage'])->name('images.destroy');
    });

    // ---------------------------------------------------------------------
    // Akses Utama Platform & RBAC System (Khusus Super Admin)
    // ---------------------------------------------------------------------
    Route::middleware(['role:super-admin'])->group(function () {
        // CRUD Manajemen GOR
        Route::get('/gors', [GorManagementController::class, 'index'])->name('gors.index');
        Route::post('/gors', [GorManagementController::class, 'store'])->name('gors.store');
        Route::put('/gors/{id}', [GorManagementController::class, 'update'])->name('gors.update');
        Route::delete('/gors/{id}', [GorManagementController::class, 'destroy'])->name('gors.destroy');

        // Pengelolaan Foto/Galeri GOR
        Route::post('/gors/{id}/images', [GorManagementController::class, 'storeImages'])->name('gors.images.store');
        Route::post('/gors/{gor}/images/{image}/set-cover', [GorManagementController::class, 'setCover'])->name('gors.images.set-cover');
        Route::delete('/gors/images/{image}', [GorManagementController::class, 'deleteImage'])->name('gors.images.destroy');

        // CRUD Manajemen User (Kasir / Admin GOR / Staff)
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::put('/users/{id}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])->name('users.destroy');

        // CRUD Manajemen Role
        Route::get('/roles', [RoleManagementController::class, 'index'])->name('roles.index');
        Route::post('/roles', [RoleManagementController::class, 'store'])->name('roles.store');
        Route::put('/roles/{id}', [RoleManagementController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{id}', [RoleManagementController::class, 'destroy'])->name('roles.destroy');

        // CRUD Manajemen Permission
        Route::get('/permissions', [PermissionManagementController::class, 'index'])->name('permissions.index');
        Route::post('/permissions', [PermissionManagementController::class, 'store'])->name('permissions.store');
        Route::put('/permissions/{id}', [PermissionManagementController::class, 'update'])->name('permissions.update');
        Route::delete('/permissions/{id}', [PermissionManagementController::class, 'destroy'])->name('permissions.destroy');

        // CRUD Manajemen District / Wilayah
        Route::get('/districts', [DistrictManagementController::class, 'index'])->name('districts.index');
        Route::post('/districts', [DistrictManagementController::class, 'store'])->name('districts.store');
        Route::put('/districts/{id}', [DistrictManagementController::class, 'update'])->name('districts.update');
        Route::delete('/districts/{id}', [DistrictManagementController::class, 'destroy'])->name('districts.destroy');
    });

});
