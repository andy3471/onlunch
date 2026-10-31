<?php

declare(strict_types=1);

namespace App\DataTransferObjects;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class UserRowData extends Data
{
    /** @param  list<string>  $teamNames */
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public bool $isAdmin,
        public array $teamNames,
        public bool $isScheduled,
    ) {}
}
