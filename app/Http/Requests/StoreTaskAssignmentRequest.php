<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Site;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        /** @var Site $site */
        $site = resolve('currentSite');

        $rules = [
            'date'       => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time'   => ['required', 'date_format:H:i', 'after:start_time'],
            'task_name'  => ['required', 'string', 'max:100'],
        ];

        if ($this->user()?->isSiteAdminFor($site)) {
            $rules['user_id'] = [
                'nullable',
                'uuid',
                Rule::in($site->members()->pluck('users.id')->all()),
            ];
        }

        return $rules;
    }
}
