<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Site;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTeamRequest extends FormRequest
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

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('teams', 'name')->where('site_id', $site->id),
            ],
            'minimum_available_staff' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
