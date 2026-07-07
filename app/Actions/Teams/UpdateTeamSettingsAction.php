<?php

declare(strict_types=1);

namespace App\Actions\Teams;

use App\Actions\Concerns\AsAction;
use App\Models\Site;
use App\Models\Team;
use App\Models\WorkingHourPreset;
use App\Support\TimeInput;

class UpdateTeamSettingsAction
{
    use AsAction;

    /** @param  array<int, array<string, mixed>>  $presets */
    public function execute(
        Site $site,
        Team $team,
        array $teamSettings,
        array $siteSettings,
        array $presets,
    ): Team {
        $site->update([
            'register_enabled'       => $siteSettings['register_enabled'],
            'reset_password_enabled' => $siteSettings['reset_password_enabled'],
        ]);

        $team->update([
            'time_off_auto_approve' => $teamSettings['time_off_auto_approve'],
            'default_task'          => $teamSettings['default_task'],
        ]);

        $this->syncWorkingHourPresets($site, $presets);

        return $team->refresh();
    }

    /** @param  array<int, array<string, mixed>>  $presets */
    protected function syncWorkingHourPresets(Site $site, array $presets): void
    {
        $keptIds = [];

        foreach (array_values($presets) as $index => $presetData) {
            $attributes = [
                'name'       => $presetData['name'],
                'start_time' => TimeInput::forStorage($presetData['start_time']),
                'end_time'   => TimeInput::forStorage($presetData['end_time']),
                'sort_order' => $index,
            ];

            if (! empty($presetData['id'])) {
                $preset = $site->workingHourPresets()->find($presetData['id']);

                if ($preset) {
                    $preset->update($attributes);
                    $keptIds[] = $preset->id;

                    continue;
                }
            }

            $created   = $site->workingHourPresets()->create($attributes);
            $keptIds[] = $created->id;
        }

        $site->workingHourPresets()
            ->whereNotIn('id', $keptIds)
            ->each(function (WorkingHourPreset $preset): void {
                if ($preset->memberships()->exists()) {
                    return;
                }

                $preset->delete();
            });
    }
}
