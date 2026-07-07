<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Actions\Onboarding\CompleteOnboardingAction;
use App\DataTransferObjects\WorkingHourPresetData;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompleteOnboardingRequest;
use App\Models\Site;
use App\Models\WorkingHourPreset;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class OnboardingController extends Controller
{
    public function __construct(
        private readonly CompleteOnboardingAction $completeOnboarding,
    ) {}

    public function show(): Response|RedirectResponse
    {
        /** @var Site $site */
        $site = resolve('currentSite');
        $user = auth()->user();

        if ($user->hasCompletedOnboardingFor($site)) {
            return redirect('/');
        }

        $presets = $site->workingHourPresets->map(fn (WorkingHourPreset $preset): WorkingHourPresetData => WorkingHourPresetData::from([
            'id'        => $preset->id,
            'name'      => $preset->name,
            'startTime' => mb_substr((string) $preset->getRawOriginal('start_time'), 0, 5),
            'endTime'   => mb_substr((string) $preset->getRawOriginal('end_time'), 0, 5),
            'label'     => $preset->label(),
        ]));

        return Inertia::render('Onboarding', [
            'presets' => $presets,
        ]);
    }

    public function store(CompleteOnboardingRequest $request): RedirectResponse
    {
        /** @var Site $site */
        $site = resolve('currentSite');
        $user = auth()->user();

        $this->completeOnboarding->execute(
            $site,
            $user,
            $request->validated('working_hour_preset_id'),
        );

        return redirect('/')->with('message', 'Welcome! Your working hours have been saved.');
    }
}
