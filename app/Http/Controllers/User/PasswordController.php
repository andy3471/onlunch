<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Actions\User\ChangePasswordAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PasswordController extends Controller
{
    public function __construct(
        private readonly ChangePasswordAction $changePassword,
    ) {}

    public function show(): Response
    {
        return Inertia::render('Auth/ChangePassword');
    }

    public function update(ChangePasswordRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $this->authorize('update', $user);

        $this->changePassword->execute($user, $request->validated('newpassword'));

        return redirect('/')->with('message', 'Password Changed');
    }
}
