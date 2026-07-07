<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Site;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompleteOnboardingRequest extends FormRequest
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

        return [
            'working_hour_preset_id' => [
                'required',
                'uuid',
                Rule::exists('working_hour_presets', 'id')->where('site_id', $site->id),
            ],
        ];
    }
}
