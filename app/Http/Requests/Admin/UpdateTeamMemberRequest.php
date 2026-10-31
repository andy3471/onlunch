<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Site;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamMemberRequest extends FormRequest
{
    use ValidatesTeamMemberships;

    public function authorize(): bool
    {
        $site = app()->bound(Site::class) ? app(Site::class) : null;

        return $site instanceof Site && $this->user()?->isSiteAdminFor($site);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $site = app(Site::class);

        return [
            'is_admin' => ['required', 'boolean'],
            ...$this->siteMemberRules($site),
            ...$this->teamMembershipRules(),
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $this->validateTeamMemberships($validator, app(Site::class));
    }
}
