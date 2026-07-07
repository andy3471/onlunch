<?php

declare(strict_types=1);

namespace App\Http\Controllers\Brochure;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterTeamRequest;
use App\Models\Site;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class SiteRegistrationController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Brochure/Register');
    }

    public function store(RegisterTeamRequest $request): RedirectResponse
    {
        $this->authorize('create', Site::class);

        $validated = $request->validated();

        return DB::transaction(function () use ($request, $validated): RedirectResponse {
            $site = Site::create([
                'name'                   => $validated['team_name'],
                'slug'                   => $validated['team_slug'],
                'register_enabled'       => true,
                'reset_password_enabled' => false,
            ]);

            $team = $site->teams()->create([
                'name' => 'Everyone',
            ]);

            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $site->members()->attach($user->id, ['is_site_admin' => true]);
            $team->members()->attach($user->id, ['is_scheduled' => true]);

            Auth::login($user);

            $protocol = $request->secure() ? 'https' : 'http';
            $domain   = config('app.domain');

            return redirect()->away("{$protocol}://{$site->slug}.{$domain}/manage/users");
        });
    }
}
