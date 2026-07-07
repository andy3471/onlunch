<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Site;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateTeamMemberRequest extends FormRequest
{
    use ValidatesTeamMemberships;

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
        $site   = resolve('currentSite');
        $userId = (string) $this->route('user');

        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'password' => ['nullable', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised()],
            'is_admin' => ['required', 'boolean'],
            ...$this->siteMemberRules($site),
            ...$this->teamMembershipRules(),
        ];
    }

    public function withValidator(Validator $validator): void
    {
        /** @var Site $site */
        $site = resolve('currentSite');

        $this->validateTeamMemberships($validator, $site);
    }
}
