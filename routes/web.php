<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataTableController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\OpdController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PreRegisteredPatientController;
use App\Http\Controllers\PreRegTrackingController;
use App\Http\Controllers\RegisterDoctorController;
use App\Http\Controllers\RegisterOpdController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect('/login');
});

Route::get('/token', function () {
    return csrf_token();
});

// Login
Route::get('/login', [SessionController::class, 'create'])->name('login');
Route::post('/login', [SessionController::class, 'store']);
Route::post('/logout', [SessionController::class, 'destroy']);

Route::post('/verify-password', [UserController::class, 'verifyPassword']);


Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard')
    ->middleware('auth');


Route::get('/pre-register', [PreRegisteredPatientController::class, 'create'])->name('pre-reg.create');
Route::post('/pre-register/store', [PreRegisteredPatientController::class, 'store'])->name('pre-reg.store');
Route::get('/pre-register/instructions', [PreRegisteredPatientController::class, 'instructions'])->name('pre-reg.instructions');




Route::get('/pre-register/track', [PreRegTrackingController::class, 'index'])->name('pre-reg.tracking.index');
Route::get('/pre-register/track/search', [PreRegTrackingController::class, 'show'])->name('pre-reg.tracking.show');



Route::get('/users/pre-reg', [PreRegisteredPatientController::class, 'index'])->name('users.pre-reg.index')
    ->middleware('auth');
Route::get('/users/pre-reg/{user_id}', [PreRegisteredPatientController::class, 'show'])->name('users.pre-reg.show')
    ->middleware('auth');
Route::delete('/users/pre-reg/{user_id}', [PreRegisteredPatientController::class, 'destroy'])->name('users.pre-reg.destroy')
    ->middleware('auth');
Route::get('/users/pre-reg/create', [PreRegisteredPatientController::class, 'create'])->name('users.pre-reg.create'); // TOD0: Implement this



// Patient
Route::get('/users/patient', [PatientController::class, 'index'])->name('users.patient.index');
Route::delete('/users/patient/{user_id}', [PatientController::class, 'destroy'])->name('users.patient.destroy');
Route::get('/users/patient/create', [PatientController::class, 'create'])->name('users.patient.create'); // TOD0: Implement this


// OPD
Route::get('/users/opd', [OpdController::class, 'index'])->name('users.opd.index');
Route::get('/users/opd/create', [OpdController::class, 'create'])->name('users.opd.create');
Route::post('/users/opd/validate-store-request', [OpdController::class, 'validateStoreRequest']);
Route::post('/users/opd/store', [OpdController::class, 'store'])->name('users.opd.store');
Route::get('/users/opd/{user_id}', [OpdController::class, 'show'])->name('users.opd.show'); // TOD0: Implement this
Route::delete('/users/opd/{user_id}', [OpdController::class, 'destroy'])->name('users.opd.destroy');



// Doctor
Route::get('/users/doctor', [DoctorController::class, 'index'])->name('users.doctor.index');
Route::get('/users/doctor/create', [DoctorController::class, 'create'])->name('users.doctor.create'); // TOD0: Implement this
Route::post('/users/doctor/store', [DoctorController::class, 'store'])->name('users.doctor.store'); // TOD0: Implement this
Route::post('/users/doctor/validate-store-request', [DoctorController::class, 'validateStoreRequest']); // TOD0: Implement this
Route::get('/users/doctor/{user_id}', [DoctorController::class, 'show'])->name('users.doctor.show'); // TOD0: Implement this
Route::delete('/users/doctor/{user_id}', [DoctorController::class, 'destroy'])->name('users.doctor.destroy');
