<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

use App\Models\WorkingHourPreset;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class WorkingHourPresetData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $startTime,
        public string $endTime,
        public string $label,
    ) {}

    public static function fromModel(WorkingHourPreset $preset): self
    {
        return new self(
            id: $preset->id,
            name: $preset->name,
            startTime: $preset->start_time,
            endTime: $preset->end_time,
            label: $preset->label(),
        );
    }
}
