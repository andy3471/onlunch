<?php

declare(strict_types=1);

namespace App\Enums;

enum ScheduleVisibility: string
{
    case Everyone  = 'everyone';
    case Team      = 'team';
    case Approvers = 'approvers';
    case Private   = 'private';
}
