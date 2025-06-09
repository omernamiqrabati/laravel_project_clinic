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
use App\Http\Controllers\Admin\ReceptionistController;

// Root route - redirect to admin dashboard
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Admin Dashboard Route
Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

// Admin resource routes
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

    // Receptionists Routes
    Route::get('receptionists', [ReceptionistController::class, 'index'])->name('receptionists.index');
    Route::get('receptionists/create', [ReceptionistController::class, 'create'])->name('receptionists.create');
    Route::post('receptionists', [ReceptionistController::class, 'store'])->name('receptionists.store');
    Route::get('receptionists/{id}/edit', [ReceptionistController::class, 'edit'])->name('receptionists.edit');
    Route::put('receptionists/{id}', [ReceptionistController::class, 'update'])->name('receptionists.update');
    Route::delete('receptionists/{id}', [ReceptionistController::class, 'destroy'])->name('receptionists.destroy');

    // Treatments Routes
    Route::resource('treatments', TreatmentController::class);
    
    // Invoices Routes
    Route::resource('invoices', InvoiceController::class);
    
    // Payments Routes
    Route::resource('payments', PaymentController::class);
    
    // User Profiles Routes
    Route::resource('user_profiles', UserProfileController::class);
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