<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataTableController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\OpdController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PreRegController;
use App\Http\Controllers\RegisterDoctorController;
use App\Http\Controllers\RegisterOpdController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect('/login');
});

// Login
Route::get('/login', [SessionController::class, 'create'])->name('login');
Route::post('/login', [SessionController::class, 'store']);
Route::post('/logout', [SessionController::class, 'destroy']);

Route::get('/dashboard', [DashboardController::class, 'show'])->middleware('auth')->name('dashboard');

Route::get('/users/pre-reg', [PreRegController::class, 'index'])->middleware('auth')->name('users.pre-reg.index');
Route::get('/users/patient', [PatientController::class, 'index'])->middleware('auth')->name('users.patient.index');
Route::get('/users/doctor', [DoctorController::class, 'index'])->middleware('auth')->name('users.doctor.index');
Route::get('/users/opd', [OpdController::class, 'index'])->middleware('auth')->name('users.opd.index');

// Route::get('/search/pre-reg', [SearchController::class, 'indexPreReg'])->middleware('auth');
// Route::post('/search/pre-reg', [SearchController::class, 'searchPreReg'])->middleware('auth');

// Register OPD
// Route::get('/register/opd', [RegisterOpdController::class, 'create'])->middleware('auth');
// Route::post('/register/opd', [RegisterOpdController::class, 'store'])->middleware('auth');

// Register Doctor
// Route::get('/register/doctor', [RegisterDoctorController::class, 'create'])->middleware('auth');
// Route::post('/register/doctor', [RegisterDoctorController::class, 'store'])->middleware('auth');
