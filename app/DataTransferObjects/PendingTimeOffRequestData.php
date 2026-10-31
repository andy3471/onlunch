<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PendingTimeOffRequestData extends Data
{
    public function __construct(
        public string $id,
        public string $userName,
        public string $teamName,
        public ?string $date,
        public ?string $startTime,
        public ?string $endTime,
        public ?string $notes,
    ) {}
}
