<?php

declare(strict_types=1);

use App\Http\Controllers\Brochure\HomeController;
use App\Http\Controllers\Brochure\SiteRegistrationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Brochure Routes (Main Domain)
|--------------------------------------------------------------------------
|
| These routes are loaded on the main domain (lunchrota.app).
| They serve the public-facing brochure site and site registration.
|
*/

Route::get('/', HomeController::class)->name('brochure.home');

Route::get('/register', [SiteRegistrationController::class, 'create'])->name('sites.register');
Route::post('/register', [SiteRegistrationController::class, 'store'])->name('sites.store');
