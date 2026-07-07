<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Actions\Concerns\AsAction;
use App\Models\User;

class ChangePasswordAction
{
    use AsAction;

    public function execute(User $user, string $password): void
    {
        $user->password = bcrypt($password);
        $user->save();
    }
}
