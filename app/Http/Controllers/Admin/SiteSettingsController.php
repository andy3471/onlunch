<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\Sites\UpdateSiteSettingsAction;
use App\DataTransferObjects\WorkingHourPresetData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSiteSettingsRequest;
use App\Models\Site;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SiteSettingsController extends Controller
{
    public function __construct(
        private readonly UpdateSiteSettingsAction $updateSiteSettings,
    ) {}

    public function show(Site $site): Response
    {
        $team = $site->defaultTeam();

        abort_unless($team instanceof Team, 404);

        $presets = $site->workingHourPresets
            ->map(fn ($preset): WorkingHourPresetData => WorkingHourPresetData::fromModel($preset))
            ->values()
            ->all();

        $taskOptions = $team->tasks->pluck('name')->all();

        return Inertia::render('Admin/SiteSettings', [
            'siteName' => $site->name,
            'settings' => [
                'workingHourPresets'   => $presets,
                'timeOffAutoApprove'   => $team->time_off_auto_approve,
                'defaultTask'          => $team->default_task ?? 'none',
                'registerEnabled'      => $site->register_enabled,
                'resetPasswordEnabled' => $site->reset_password_enabled,
            ],
            'taskOptions' => $taskOptions,
        ]);
    }

    public function update(UpdateSiteSettingsRequest $request, Site $site): RedirectResponse
    {
        $team      = $site->defaultTeam();
        $validated = $request->validated();

        abort_unless($team instanceof Team, 404);

        $this->updateSiteSettings->execute(
            $site,
            $team,
            [
                'time_off_auto_approve' => $validated['time_off_auto_approve'],
                'default_task'          => $validated['default_task'],
            ],
            [
                'register_enabled'       => $validated['register_enabled'],
                'reset_password_enabled' => $validated['reset_password_enabled'],
            ],
            $validated['working_hour_presets'],
        );

        return redirect()->route('manage.settings.show')->with('message', 'Site settings saved.');
    }
}
