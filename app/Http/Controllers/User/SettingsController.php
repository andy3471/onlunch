<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Actions\UserSettings\UpdateUserSettingsAction;
use App\DataTransferObjects\WorkingHourPresetData;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserSettingsRequest;
use App\Models\Site;
use App\Models\WorkingHourPreset;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SettingsController extends Controller
{
    public function __construct(
        private readonly UpdateUserSettingsAction $updateUserSettings,
    ) {}

    public function show(): Response
    {
        /** @var Site $site */
        $site       = resolve('currentSite');
        $user       = auth()->user();
        $membership = $user->siteMembershipFor($site);

        $presets = $site->workingHourPresets->map(fn (WorkingHourPreset $preset): WorkingHourPresetData => WorkingHourPresetData::from([
            'id'        => $preset->id,
            'name'      => $preset->name,
            'startTime' => mb_substr((string) $preset->getRawOriginal('start_time'), 0, 5),
            'endTime'   => mb_substr((string) $preset->getRawOriginal('end_time'), 0, 5),
            'label'     => $preset->label(),
        ]));

        return Inertia::render('UserSettings', [
            'presets'                 => $presets,
            'workingHourPresetId'     => $membership?->working_hour_preset_id,
            'timeOffRequiresApproval' => $user->requiresTimeOffApproval($site),
        ]);
    }

    public function update(UpdateUserSettingsRequest $request): RedirectResponse
    {
        /** @var Site $site */
        $site = resolve('currentSite');
        $user = auth()->user();

        $this->updateUserSettings->execute(
            $site,
            $user,
            $request->validated('working_hour_preset_id'),
        );

        return redirect('/settings')->with('message', 'Settings saved.');
    }
}
