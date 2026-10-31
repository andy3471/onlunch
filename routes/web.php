<?php

declare(strict_types=1);

use App\Http\Controllers\Brochure\HomeController;
use App\Http\Controllers\Brochure\SiteRegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('brochure.home');

Route::get('/register', [SiteRegistrationController::class, 'create'])->name('sites.register');
Route::post('/register', [SiteRegistrationController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('sites.store');
