<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Actions\Onboarding\CompleteOnboardingAction;
use App\DataTransferObjects\WorkingHourPresetData;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompleteOnboardingRequest;
use App\Models\Site;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class OnboardingController extends Controller
{
    public function __construct(
        private readonly CompleteOnboardingAction $completeOnboarding,
    ) {}

    public function show(Site $site): Response|RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user->hasCompletedOnboardingFor($site)) {
            return redirect()->route('home');
        }

        $presets = $site->workingHourPresets
            ->map(fn ($preset): WorkingHourPresetData => WorkingHourPresetData::fromModel($preset))
            ->values()
            ->all();

        return Inertia::render('Onboarding', [
            'presets' => $presets,
        ]);
    }

    public function store(CompleteOnboardingRequest $request, Site $site): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $this->completeOnboarding->execute(
            $site,
            $user,
            $request->validated('working_hour_preset_id'),
        );

        return redirect()->route('home')->with('message', 'Welcome! Your working hours have been saved.');
    }
}
