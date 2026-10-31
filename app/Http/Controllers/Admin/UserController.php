<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\Users\BuildUserFormAction;
use App\Actions\Users\BuildUserIndexAction;
use App\Actions\Users\RemoveSiteMemberAction;
use App\Actions\Users\StoreTeamMemberAction;
use App\Actions\Users\UpdateTeamMemberAction;
use App\DataTransferObjects\StoreTeamMemberData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTeamMemberRequest;
use App\Http\Requests\Admin\UpdateTeamMemberRequest;
use App\Models\Site;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function __construct(
        private readonly BuildUserIndexAction $buildUserIndex,
        private readonly BuildUserFormAction $buildUserForm,
        private readonly StoreTeamMemberAction $storeTeamMember,
        private readonly UpdateTeamMemberAction $updateTeamMember,
        private readonly RemoveSiteMemberAction $removeSiteMember,
    ) {}

    public function index(Site $site): Response
    {
        return Inertia::render('Admin/Users/Index', [
            'users' => $this->buildUserIndex->execute($site),
        ]);
    }

    public function create(Site $site): Response
    {
        return Inertia::render('Admin/Users/Form', $this->buildUserForm->execute($site));
    }

    public function store(StoreTeamMemberRequest $request, Site $site): RedirectResponse
    {
        $this->storeTeamMember->execute(
            $site,
            StoreTeamMemberData::fromValidated($request->validated()),
        );

        return redirect()->route('manage.users.index')->with('message', 'User created.');
    }

    public function edit(Request $request, Site $site): Response
    {
        $user = $this->resolveSiteUser($site, (string) $request->route('user'));

        return Inertia::render('Admin/Users/Form', [
            ...$this->buildUserForm->execute($site, $user),
            'user' => [
                'id'      => $user->id,
                'name'    => $user->name,
                'email'   => $user->email,
                'isAdmin' => $user->isSiteAdminFor($site),
            ],
        ]);
    }

    public function update(UpdateTeamMemberRequest $request, Site $site): RedirectResponse
    {
        $user = $this->resolveSiteUser($site, (string) $request->route('user'));
        $this->updateTeamMember->execute($site, $user, $request->validated());

        return redirect()->route('manage.users.index')->with('message', 'User updated.');
    }

    public function destroy(Request $request, Site $site): RedirectResponse
    {
        $user = $this->resolveSiteUser($site, (string) $request->route('user'));
        $this->removeSiteMember->execute($site, $user);

        return redirect()->route('manage.users.index')->with('message', 'User removed from this site.');
    }

    protected function resolveSiteUser(Site $site, string $userId): User
    {
        return $site->members()->whereKey($userId)->firstOrFail();
    }
}
