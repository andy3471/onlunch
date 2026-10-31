<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Actions\Concerns\AsAction;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ChangePasswordAction
{
    use AsAction;

    public function execute(User $user, string $password): void
    {
        $user->forceFill([
            'password' => Hash::make($password),
        ])->save();
    }
}
