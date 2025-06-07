<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\DentistController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\TreatmentController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\UserProfileController;

// Root route - redirect to admin dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Admin dashboard route
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});



Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('user_profiles', UserProfileController::class);
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('payments', PaymentController::class);
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('invoices', InvoiceController::class);
});


Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('treatments', TreatmentController::class);
});


Route::prefix('admin')->name('admin.')->group(function () {
    // Appointments
    Route::get('appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('appointments/{id}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
    Route::put('appointments/{id}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('appointments/{id}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
});

// routes/web.php







Route::resource('dentists', DentistController::class);
Route::resource('patients', PatientController::class);



Route::prefix('admin')->name('admin.')->group(function () {
    // Patients Routes
    Route::get('patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('patients/create', [PatientController::class, 'create'])->name('patients.create');
    Route::post('patients', [PatientController::class, 'store'])->name('patients.store');
    Route::get('patients/{id}/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::put('patients/{id}', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('patients/{id}', [PatientController::class, 'destroy'])->name('patients.destroy');

    
    // Dentists Routes
    Route::get('dentists', [DentistController::class, 'index'])->name('dentists.index');
    Route::get('dentists/create', [DentistController::class, 'create'])->name('dentists.create');
    Route::post('dentists', [DentistController::class, 'store'])->name('dentists.store');
    Route::get('dentists/{id}/edit', [DentistController::class, 'edit'])->name('dentists.edit');
    Route::put('dentists/{id}', [DentistController::class, 'update'])->name('dentists.update');
    Route::delete('dentists/{id}', [DentistController::class, 'destroy'])->name('dentists.destroy');
});
// Admin appointment routes grouped like patients
Route::prefix('admin')->name('admin.')->group(function () {
    // Appointments Routes
    Route::get('appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('appointments/{id}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
    Route::put('appointments/{id}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('appointments/{id}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
});