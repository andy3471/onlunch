<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Actions\Users\UpdateUserSettingsAction;
use App\DataTransferObjects\WorkingHourPresetData;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserSettingsRequest;
use App\Models\Site;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SettingsController extends Controller
{
    public function __construct(
        private readonly UpdateUserSettingsAction $updateUserSettings,
    ) {}

    public function show(Site $site): Response
    {
        /** @var User $user */
        $user       = auth()->user();
        $membership = $user->siteMembershipFor($site);

        $presets = $site->workingHourPresets
            ->map(fn ($preset): WorkingHourPresetData => WorkingHourPresetData::fromModel($preset))
            ->values()
            ->all();

        return Inertia::render('User/Settings', [
            'presets'                 => $presets,
            'workingHourPresetId'     => $membership?->working_hour_preset_id,
            'timeOffRequiresApproval' => $user->requiresTimeOffApproval($site),
        ]);
    }

    public function update(UpdateUserSettingsRequest $request, Site $site): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $this->updateUserSettings->execute(
            $site,
            $user,
            $request->validated('working_hour_preset_id'),
        );

        return redirect()->route('settings.show')->with('message', 'Settings saved.');
    }
}
