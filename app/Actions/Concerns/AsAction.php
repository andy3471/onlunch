<?php

declare(strict_types=1);

namespace App\Actions\Concerns;

trait AsAction
{
    public static function run(mixed ...$arguments): mixed
    {
        return resolve(static::class)->execute(...$arguments);
    }
}
