<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Site;
use App\Models\Team;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTeamTaskRequest extends FormRequest
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

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tasks', 'name')->where('team_id', $team->id),
            ],
            'color' => ['nullable', 'string', 'max:7'],
        ];
    }
}
