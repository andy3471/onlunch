<?php

declare(strict_types=1);

namespace App\Enums;

enum TimeOffApprovalMode: string
{
    case SiteDefault = 'site_default';
    case Required    = 'required';
    case Auto        = 'auto';

    public static function fromNullableBool(?bool $value): self
    {
        return match ($value) {
            true  => self::Required,
            false => self::Auto,
            null  => self::SiteDefault,
        };
    }

    public function toNullableBool(): ?bool
    {
        return match ($this) {
            self::Required    => true,
            self::Auto        => false,
            self::SiteDefault => null,
        };
    }
}
