<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\DentistController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\TreatmentController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\PaymentController;

use App\Http\Controllers\Admin\ReceptionistController;

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset routes
Route::get('/forgot-password', [AuthController::class, 'showPasswordResetForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendPasswordReset'])->name('password.email');

// Password Update routes
Route::get('/auth/update-password', [AuthController::class, 'showUpdatePasswordForm'])->name('password.reset');
Route::post('/auth/update-password', [AuthController::class, 'updatePassword'])->name('password.update');

// TEMPORARY: Create test admin user route - Remove in production
Route::get('/create-test-user', function () {
    $supabaseAuth = new \App\Services\SupabaseAuthService();
    
    $result = $supabaseAuth->createUser('admin@clinic.com', 'admin123', [
        'first_name' => 'System',
        'last_name' => 'Admin',
        'role' => 'admin',
    ]);
    
    if ($result['success']) {
        return response()->json([
            'message' => 'Admin user created successfully!',
            'email' => 'admin@clinic.com',
            'password' => 'admin123',
            'role' => 'admin'
        ]);
    } else {
        return response()->json([
            'error' => $result['error']
        ], 400);
    }
})->name('create.test.user');

// TEMPORARY: Reset password for existing users - Remove in production
Route::get('/reset-admin-password/{email}', function ($email) {
    $supabaseAuth = new \App\Services\SupabaseAuthService();
    
    $result = $supabaseAuth->resetPassword($email, url('/auth/update-password'));
    
    if ($result['success']) {
        return response()->json([
            'message' => 'Password reset email sent to ' . $email,
            'note' => 'User will receive an email with reset link'
        ]);
    } else {
        return response()->json([
            'error' => $result['error']
        ], 400);
    }
})->name('reset.admin.password');

// Root route - redirect to login if not authenticated, otherwise to dashboard
Route::get('/', function () {
    if (session('authenticated')) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
});

// Admin Dashboard Route
Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard')->middleware('auth.custom');
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.alt')->middleware('auth.custom');

// Admin resource routes
Route::prefix('admin')->name('admin.')->middleware('auth.custom')->group(function () {
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
    

});

// Admin appointment routes grouped like patients
Route::prefix('admin')->name('admin.')->middleware('auth.custom')->group(function () {
    // Appointments Routes
    Route::get('appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('appointments/{id}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
    Route::put('appointments/{id}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('appointments/{id}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
    
    // Test route for debugging appointment updates
    Route::get('appointments/{id}/test-update', [AppointmentController::class, 'testUpdate'])->name('appointments.test-update');
});