<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\Teams\UpdateTeamSettingsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateTeamSettingsRequest;
use App\Models\Site;
use App\Models\Team;
use App\Models\WorkingHourPreset;
use App\Support\TimeInput;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SiteSettingsController extends Controller
{
    public function __construct(
        private readonly UpdateTeamSettingsAction $updateTeamSettings,
    ) {}

    public function show(): Response
    {
        /** @var Site $site */
        $site = resolve('currentSite');
        $team = $site->defaultTeam();

        abort_unless($team instanceof Team, 404);

        $presets = $site->workingHourPresets->map(fn (WorkingHourPreset $preset): array => [
            'id'        => $preset->id,
            'name'      => $preset->name,
            'startTime' => TimeInput::normalize((string) $preset->getRawOriginal('start_time')),
            'endTime'   => TimeInput::normalize((string) $preset->getRawOriginal('end_time')),
            'label'     => $preset->label(),
        ]);

        $taskOptions = $team->tasks->pluck('name')->all();

        return Inertia::render('Admin/TeamSettings', [
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

    public function update(UpdateTeamSettingsRequest $request): RedirectResponse
    {
        /** @var Site $site */
        $site      = resolve('currentSite');
        $team      = $site->defaultTeam();
        $validated = $request->validated();

        abort_unless($team instanceof Team, 404);

        $this->updateTeamSettings->execute(
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

        return redirect('/manage/settings')->with('message', 'Team settings saved.');
    }
}
