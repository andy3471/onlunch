<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\User\ChangePasswordAction;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ChangePasswordJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public string $userId,
        public string $password,
    ) {}

    public function handle(ChangePasswordAction $changePassword): void
    {
        $user = User::query()->findOrFail($this->userId);

        $changePassword->execute($user, $this->password);
    }
}
