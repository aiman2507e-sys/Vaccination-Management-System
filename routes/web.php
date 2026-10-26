<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\HospitalController;

/*
|--------------------------------------------------------------------------
| Web Routes - Vaccination Management System
|--------------------------------------------------------------------------
|
| Role-based application routes structured for:
| - Public Informational Pages & Immunization Schedule
| - Authentication & Multi-Role Registration
| - Admin Control Panel
| - Parent Self-Service Portal
| - Hospital Facility Portal
|
*/

// =========================================================================
// 1. PUBLIC ROUTES
// =========================================================================
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/schedule', [PublicController::class, 'vaccineSchedule'])->name('public.schedule');
Route::get('/hospitals', [PublicController::class, 'hospitalsList'])->name('public.hospitals');

// =========================================================================
// 2. AUTHENTICATION ROUTES
// =========================================================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register/parent', [AuthController::class, 'showParentRegister'])->name('register.parent');
Route::post('/register/parent', [AuthController::class, 'registerParent']);

Route::get('/register/hospital', [AuthController::class, 'showHospitalRegister'])->name('register.hospital');
Route::post('/register/hospital', [AuthController::class, 'registerHospital']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =========================================================================
// 3. ADMIN PORTAL ROUTES
// =========================================================================
Route::prefix('admin')
    ->as('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        // Admin Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // All Child Details (SRS Module)
        Route::get('/children', [AdminController::class, 'allChildren'])->name('children.index');
        Route::get('/children/{id}', [AdminController::class, 'childProfile'])->name('children.show');

        // Date & Time of Vaccination (SRS Module)
        Route::get('/vaccination-dates', [AdminController::class, 'vaccinationDates'])->name('vaccination.dates');

        // Report of Vaccination (SRS Module)
        Route::get('/reports', [AdminController::class, 'vaccinationReport'])->name('reports.index');
        Route::get('/reports/export', [AdminController::class, 'exportReportCsv'])->name('reports.export');

        // List of Vaccine (Available / Unavailable & CRUD) (SRS Module)
        Route::get('/vaccines', [AdminController::class, 'vaccinesIndex'])->name('vaccines.index');
        Route::post('/vaccines', [AdminController::class, 'storeVaccine'])->name('vaccines.store');
        Route::put('/vaccines/{id}', [AdminController::class, 'updateVaccine'])->name('vaccines.update');
        Route::patch('/vaccines/{id}/toggle', [AdminController::class, 'toggleVaccineStatus'])->name('vaccines.toggle');
        Route::delete('/vaccines/{id}', [AdminController::class, 'deleteVaccine'])->name('vaccines.destroy');

        // Requests from Parents (Approve / Reject) (SRS Module)
        Route::get('/requests', [AdminController::class, 'parentRequests'])->name('requests.index');
        Route::patch('/requests/{id}/approve', [AdminController::class, 'approveRequest'])->name('requests.approve');
        Route::patch('/requests/{id}/reject', [AdminController::class, 'rejectRequest'])->name('requests.reject');

        // Hospital Management (Add, Update, Delete, List) (SRS Module)
        Route::get('/hospitals', [AdminController::class, 'hospitalsIndex'])->name('hospitals.index');
        Route::post('/hospitals', [AdminController::class, 'storeHospital'])->name('hospitals.store');
        Route::put('/hospitals/{id}', [AdminController::class, 'updateHospital'])->name('hospitals.update');
        Route::delete('/hospitals/{id}', [AdminController::class, 'deleteHospital'])->name('hospitals.destroy');

        // Booking Details (SRS Module)
        Route::get('/bookings', [AdminController::class, 'bookingDetails'])->name('bookings.index');
    });

// =========================================================================
// 4. PARENT PORTAL ROUTES
// =========================================================================
Route::prefix('parent')
    ->as('parent.')
    ->middleware(['auth', 'role:parent'])
    ->group(function () {
        // Parent Dashboard with upcoming notifications
        Route::get('/dashboard', [ParentController::class, 'dashboard'])->name('dashboard');

        // My Profile (SRS Module)
        Route::get('/profile', [ParentController::class, 'profile'])->name('profile');
        Route::put('/profile', [ParentController::class, 'updateProfile'])->name('profile.update');

        // Details of Child (SRS Module)
        Route::get('/children', [ParentController::class, 'childrenIndex'])->name('children.index');
        Route::get('/children/create', [ParentController::class, 'createChild'])->name('children.create');
        Route::post('/children', [ParentController::class, 'storeChild'])->name('children.store');
        Route::get('/children/{id}', [ParentController::class, 'childDetail'])->name('children.show');
        Route::get('/children/{id}/edit', [ParentController::class, 'editChild'])->name('children.edit');
        Route::put('/children/{id}', [ParentController::class, 'updateChild'])->name('children.update');

        // Vaccination Dates notification view (SRS Module)
        Route::get('/vaccination-dates', [ParentController::class, 'vaccinationDates'])->name('vaccination.dates');

        // Book Hospital / Request Appointment (SRS Module)
        Route::get('/book-hospital', [ParentController::class, 'bookHospital'])->name('book.hospital');
        Route::post('/book-hospital', [ParentController::class, 'storeBooking'])->name('book.hospital.store');

        // Report of Vaccination Taken (SRS Module)
        Route::get('/reports', [ParentController::class, 'vaccinationReport'])->name('reports.index');
    });

// =========================================================================
// 5. HOSPITAL FACILITY ROUTES
// =========================================================================
Route::prefix('hospital')
    ->as('hospital.')
    ->middleware(['auth', 'role:hospital'])
    ->group(function () {
        // Hospital Dashboard
        Route::get('/dashboard', [HospitalController::class, 'dashboard'])->name('dashboard');

        // Appointment Queue
        Route::get('/appointments', [HospitalController::class, 'appointments'])->name('appointments.index');
        Route::get('/appointments/{id}/update-status', [HospitalController::class, 'showUpdateStatus'])->name('appointments.status.form');

        // Update Vaccine Status (SRS Module: Vaccinated or Not Vaccinated)
        Route::post('/appointments/{id}/update-status', [HospitalController::class, 'updateVaccineStatus'])->name('appointments.status.update');

        // Hospital Profile
        Route::get('/profile', [HospitalController::class, 'profile'])->name('profile');
        Route::put('/profile', [HospitalController::class, 'updateProfile'])->name('profile.update');
    });
