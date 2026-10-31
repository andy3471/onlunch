<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Site;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreTeamMemberRequest extends FormRequest
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
        $site           = app(Site::class);
        $existingUser   = User::query()->where('email', $this->input('email'))->exists();

        return [
            'name'     => [$existingUser ? 'nullable' : 'required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255'],
            'password' => [
                $existingUser ? 'nullable' : 'required',
                'string',
                'confirmed',
                Password::defaults(),
            ],
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
