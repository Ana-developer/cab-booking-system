<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\DashboardController;


Route::get('/', [DashboardController::class, 'index']);

/* ---------------- ADMIN COMPANIES ---------------- */
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('companies', CompanyController::class);
});

/* ---------------- ADMIN CARS ---------------- */
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('cars', CarController::class);
});

Route::get('/admin/cars/{car}/calendar', [CarController::class, 'calendar'])
    ->name('admin.cars.calendar');

Route::post('/admin/car-bookings/store', [CarController::class, 'storeBooking'])
    ->name('admin.car.bookings.store');

/* ---------------- ADMIN BOOKINGS ---------------- */
Route::get('/admin/bookings/calendar', [AdminBookingController::class, 'calendar'])
    ->name('admin.bookings.calendar');

Route::post('/admin/bookings', [AdminBookingController::class, 'store'])
    ->name('admin.bookings.store');

    Route::put('/admin/bookings/{booking}', [AdminBookingController::class, 'update'])
    ->name('admin.bookings.update');

Route::delete('/admin/bookings/{booking}', [AdminBookingController::class, 'destroy'])
    ->name('admin.bookings.destroy');

    Route::post('/admin/bookings/{booking}/move',
    [AdminBookingController::class, 'move']
)->name('admin.bookings.move');

    Route::get('/billing/{booking}', 
        [BillingController::class, 'edit'])
        ->name('billing.edit');

    Route::post('/billing/{booking}', 
        [BillingController::class, 'save'])
        ->name('billing.save');

        Route::get('/admin/billing', [BillingController::class, 'index'])
    ->name('billing.index');

Route::post('/billing/{billing}/mark-paid', 
    [BillingController::class, 'markPaid']
)->name('billing.markPaid');

Route::get('billing/{id}/invoice', 
    [BillingController::class, 'invoice']
)->name('billing.invoice');

Route::get('/billing/{id}', [BillingController::class, 'show'])
    ->name('billing.show');

    Route::post('/admin/bookings/store',
    [CarController::class, 'storeBooking'])
    ->name('admin.bookings.store');
