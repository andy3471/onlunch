<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class TeamFilterOptionData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
    ) {}
}
