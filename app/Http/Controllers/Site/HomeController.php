<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

use App\Actions\Home\BuildHomePageAction;
use App\Models\Site;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __construct(
        private readonly BuildHomePageAction $buildHomePage,
    ) {}

    public function __invoke(Request $request): Response|RedirectResponse
    {
        /** @var Site $site */
        $site = resolve('currentSite');
        $date = $request->date('date') ?? \Illuminate\Support\Facades\Date::today();
        $user = $request->user();

        if ($user !== null && ! $user->hasCompletedOnboardingFor($site)) {
            return redirect('/onboarding');
        }

        return Inertia::render(
            'Home',
            $this->buildHomePage->execute($site, $date, $user)->toArray(),
        );
    }
}
