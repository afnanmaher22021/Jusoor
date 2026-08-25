<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HourTrackingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OpportunityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'landing'])->name('landing');
Route::get('/browse', [PublicController::class, 'browse'])->name('browse');
Route::get('/opportunities/{opportunity}', [OpportunityController::class, 'show'])->name('opportunities.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    Route::post('/opportunities/{opportunity}/apply', [ApplicationController::class, 'apply'])
        ->middleware('role:volunteer')
        ->name('opportunities.apply');

    Route::get('/dashboard', function () {
        return redirect()->route('home');
    });

    Route::middleware('role:volunteer')->group(function () {
        Route::get('/volunteer/dashboard', [DashboardController::class, 'volunteer'])->name('volunteer.dashboard');
        Route::get('/volunteer/applications', [ApplicationController::class, 'myApplications'])->name('volunteer.applications');
        Route::post('/volunteer/applications/{application}/cancel', [ApplicationController::class, 'cancel'])->name('volunteer.applications.cancel');
        Route::get('/volunteer/hours', [HourTrackingController::class, 'history'])->name('volunteer.hours');
        Route::get('/volunteer/certificate', [CertificateController::class, 'show'])->name('volunteer.certificate');
    });

    Route::middleware('role:organization')->group(function () {
        Route::get('/organization/dashboard', [DashboardController::class, 'organization'])->name('organization.dashboard');
        Route::get('/organization/opportunities', [OpportunityController::class, 'index'])->name('organization.opportunities.index');
        Route::get('/organization/opportunities/create', [OpportunityController::class, 'create'])->name('organization.opportunities.create');
        Route::post('/organization/opportunities', [OpportunityController::class, 'store'])->name('organization.opportunities.store');
        Route::get('/organization/opportunities/{opportunity}/edit', [OpportunityController::class, 'edit'])->name('organization.opportunities.edit');
        Route::put('/organization/opportunities/{opportunity}', [OpportunityController::class, 'update'])->name('organization.opportunities.update');
        Route::delete('/organization/opportunities/{opportunity}', [OpportunityController::class, 'destroy'])->name('organization.opportunities.destroy');

        Route::get('/organization/applications', [ApplicationController::class, 'indexOrganization'])->name('organization.applications');
        Route::post('/organization/applications/{application}/respond', [ApplicationController::class, 'respond'])->name('organization.applications.respond');

        Route::get('/organization/hours', [HourTrackingController::class, 'selectOpportunity'])->name('organization.hours.select');
        Route::get('/organization/hours/{opportunity}', [HourTrackingController::class, 'manage'])->name('organization.hours.manage');
        Route::post('/organization/hours/{opportunity}', [HourTrackingController::class, 'store'])->name('organization.hours.store');
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/admin/organizations/{organization}/verify', [AdminController::class, 'toggleVerify'])->name('admin.organizations.verify');
    });
});

Route::get('/home', function () {
    return match (auth()->user()?->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'organization' => redirect()->route('organization.dashboard'),
        default => redirect()->route('volunteer.dashboard'),
    };
})->middleware('auth')->name('home');
