<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\SiteSettingsController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\TimeOffRequestController as AdminTimeOffRequestController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\LunchBookingController;
use App\Http\Controllers\Site\OnboardingController;
use App\Http\Controllers\Site\TaskAssignmentController;
use App\Http\Controllers\Site\TimeOffRequestController;
use App\Http\Controllers\User\PasswordController;
use App\Http\Controllers\User\SettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| These routes are loaded on tenant subdomains ({slug}.lunchrota.app).
| The ResolveTenantFromSubdomain middleware runs before these routes,
| making the current site available via app('currentSite').
|
*/

// Guest routes
Route::middleware('guest')->group(function (): void {
    // Login
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);

    // Registration (guarded by team setting)
    Route::middleware('team.feature:register_enabled')->group(function (): void {
        Route::get('register', [RegisterController::class, 'create'])->name('register');
        Route::post('register', [RegisterController::class, 'store']);
    });

    // Password Reset (guarded by team setting)
    Route::middleware('team.feature:reset_password_enabled')->group(function (): void {
        Route::get('forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
        Route::post('forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
        Route::get('reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
        Route::post('reset-password', [ResetPasswordController::class, 'store'])->name('password.update');
    });
});

// Authenticated routes
Route::middleware('auth')->group(function (): void {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('change-password', [PasswordController::class, 'show'])->name('password.change');
    Route::put('change-password', [PasswordController::class, 'update']);

    Route::get('onboarding', [OnboardingController::class, 'show'])->name('onboarding.show');
    Route::post('onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');

    Route::middleware('onboarding.complete')->group(function (): void {
        Route::get('/', HomeController::class)->name('home');

        Route::get('settings', [SettingsController::class, 'show'])->name('settings.show');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');

        Route::post('lunch-booking', [LunchBookingController::class, 'store'])->name('lunch-booking.store');
        Route::put('lunch-booking/{lunchBooking}', [LunchBookingController::class, 'update'])->name('lunch-booking.update');
        Route::delete('lunch-booking', [LunchBookingController::class, 'destroy'])->name('lunch-booking.destroy');
        Route::post('time-off-requests', [TimeOffRequestController::class, 'store'])->name('time-off-requests.store');
        Route::post('task-assignments', [TaskAssignmentController::class, 'store'])->name('task-assignments.store');
        Route::put('task-assignments/{taskAssignment}', [TaskAssignmentController::class, 'update'])->name('task-assignments.update');
        Route::delete('task-assignments/{taskAssignment}', [TaskAssignmentController::class, 'destroy'])->name('task-assignments.destroy');

        Route::redirect('/admin', '/manage/users')->name('admin.redirect');

        Route::prefix('manage')
            ->name('manage.')
            ->group(function (): void {
                Route::middleware('time_off.manage')->group(function (): void {
                    Route::get('time-off-requests', [AdminTimeOffRequestController::class, 'index'])->name('time-off-requests.index');
                    Route::post('time-off-requests/{timeOffRequest}/approve', [AdminTimeOffRequestController::class, 'approve'])->name('time-off-requests.approve');
                    Route::post('time-off-requests/{timeOffRequest}/reject', [AdminTimeOffRequestController::class, 'reject'])->name('time-off-requests.reject');
                });

                Route::middleware('admin')->group(function (): void {
                    Route::get('teams', [TeamController::class, 'index'])->name('teams.index');
                    Route::get('teams/create', [TeamController::class, 'create'])->name('teams.create');
                    Route::post('teams', [TeamController::class, 'store'])->name('teams.store');
                    Route::get('teams/{team}/edit', [TeamController::class, 'edit'])->name('teams.edit');
                    Route::put('teams/{team}', [TeamController::class, 'update'])->name('teams.update');

                    Route::get('users', [UserController::class, 'index'])->name('users.index');
                    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
                    Route::post('users', [UserController::class, 'store'])->name('users.store');
                    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
                    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
                    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

                    Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
                    Route::get('tasks/create', [TaskController::class, 'create'])->name('tasks.create');
                    Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');
                    Route::get('tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
                    Route::put('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
                    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

                    Route::get('settings', [SiteSettingsController::class, 'show'])->name('settings.show');
                    Route::put('settings', [SiteSettingsController::class, 'update'])->name('settings.update');
                });
            });
    });
});
