<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

class SitePolicy
{
    /**
     * Determine whether the user can create a new site.
     * Open registration: anyone (including guests) can register.
     */
    public function create(?User $user): bool
    {
        return true;
    }
}
