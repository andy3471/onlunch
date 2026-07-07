<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

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
}
