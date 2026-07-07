<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, string>> */
    public function rules(): array
    {
        return [
            'team_name' => ['required', 'string', 'max:255'],
            'team_slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:sites,slug'],
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'team_slug.unique'       => 'This team URL is already taken.',
            'team_slug.alpha_dash'   => 'The team URL may only contain letters, numbers, dashes, and underscores.',
            'email.unique'           => 'An account with this email already exists.',
        ];
    }
}
