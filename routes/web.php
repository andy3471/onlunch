<?php

declare(strict_types=1);

use App\Http\Controllers\Brochure\HomeController;
use App\Http\Controllers\Brochure\SiteRegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('brochure.home');

Route::resource('sites', SiteRegistrationController::class)
    ->only(['create', 'store'])
    ->middlewareFor('store', 'throttle:6,1');
