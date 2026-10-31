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

Route::middleware('guest')->group(function (): void {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store'])->middleware('throttle:login');

    Route::middleware('team.feature:register_enabled')->group(function (): void {
        Route::get('register', [RegisterController::class, 'create'])->name('register');
        Route::post('register', [RegisterController::class, 'store'])->middleware('throttle:6,1');
    });

    Route::middleware('team.feature:reset_password_enabled')->group(function (): void {
        Route::get('forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
        Route::post('forgot-password', [ForgotPasswordController::class, 'store'])
            ->middleware('throttle:6,1')
            ->name('password.email');
        Route::get('reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
        Route::post('reset-password', [ResetPasswordController::class, 'store'])
            ->middleware('throttle:6,1')
            ->name('password.update');
    });
});

Route::middleware(['auth', 'site.member'])->group(function (): void {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('change-password', [PasswordController::class, 'show'])->name('password.change');
    Route::put('change-password', [PasswordController::class, 'update']);

    Route::singleton('onboarding', OnboardingController::class)
        ->creatable()
        ->only(['show', 'store']);

    Route::middleware('onboarding.complete')->group(function (): void {
        Route::get('/', HomeController::class)->name('home');

        Route::singleton('settings', SettingsController::class)->only(['show', 'update']);

        Route::resource('lunch-bookings', LunchBookingController::class)
            ->only(['store', 'update', 'destroy'])
            ->parameters(['lunch-bookings' => 'lunchBooking']);

        Route::resource('time-off-requests', TimeOffRequestController::class)
            ->only(['store']);

        Route::resource('task-assignments', TaskAssignmentController::class)
            ->only(['store', 'update', 'destroy'])
            ->parameters(['task-assignments' => 'taskAssignment']);

        Route::redirect('/admin', '/manage/users')->name('admin.redirect');

        Route::prefix('manage')
            ->name('manage.')
            ->group(function (): void {
                Route::middleware('time_off.manage')->group(function (): void {
                    Route::resource('time-off-requests', AdminTimeOffRequestController::class)
                        ->only(['index']);

                    Route::post('time-off-requests/{timeOffRequest}/approve', [AdminTimeOffRequestController::class, 'approve'])
                        ->name('time-off-requests.approve');
                    Route::post('time-off-requests/{timeOffRequest}/reject', [AdminTimeOffRequestController::class, 'reject'])
                        ->name('time-off-requests.reject');
                });

                Route::middleware('admin')->group(function (): void {
                    Route::resource('teams', TeamController::class)
                        ->except(['show', 'destroy']);

                    Route::resource('users', UserController::class)
                        ->except(['show']);

                    Route::resource('tasks', TaskController::class)
                        ->except(['show']);

                    Route::singleton('settings', SiteSettingsController::class)
                        ->only(['show', 'update']);
                });
            });
    });
});
