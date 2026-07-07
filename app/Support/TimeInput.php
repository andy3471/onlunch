<?php

declare(strict_types=1);

namespace App\Support;

class TimeInput
{
    public static function normalize(?string $time): ?string
    {
        if ($time === null || $time === '') {
            return null;
        }

        return mb_substr($time, 0, 5);
    }

    public static function forStorage(string $time): string
    {
        return mb_strlen($time) === 5 ? $time.':00' : $time;
    }
}
