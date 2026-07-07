<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Site;
use App\Models\Team;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeamSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Site|null $site */
        $site = app()->bound('currentSite') ? resolve('currentSite') : null;

        return $site instanceof Site && $this->user()?->isSiteAdminFor($site);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        /** @var Site $site */
        $site = resolve('currentSite');
        $team = $site->defaultTeam();

        abort_unless($team instanceof Team, 404);

        $taskNames = $team->tasks()->pluck('name')->all();

        return [
            'working_hour_presets'              => ['required', 'array', 'min:1'],
            'working_hour_presets.*.id'         => ['nullable', 'uuid'],
            'working_hour_presets.*.name'       => ['required', 'string', 'max:255'],
            'working_hour_presets.*.start_time' => ['required', 'date_format:H:i'],
            'working_hour_presets.*.end_time'   => ['required', 'date_format:H:i'],
            'time_off_auto_approve'             => ['required', 'boolean'],
            'default_task'                      => ['required', Rule::in(['none', ...$taskNames])],
            'register_enabled'                  => ['required', 'boolean'],
            'reset_password_enabled'            => ['required', 'boolean'],
        ];
    }
}
