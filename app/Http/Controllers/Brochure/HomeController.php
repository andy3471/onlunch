<?php

declare(strict_types=1);

namespace App\Http\Controllers\Brochure;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Brochure/Landing');
    }
}
