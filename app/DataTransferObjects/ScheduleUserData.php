<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ScheduleUserData extends Data
{
    public function __construct(
        public string $userId,
        public string $userName,
    ) {}
}
