<?php

declare(strict_types=1);

namespace App\Http\Controllers\Brochure;

use App\Actions\Sites\RegisterSiteAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterTeamRequest;
use App\Models\Site;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class SiteRegistrationController extends Controller
{
    public function __construct(
        private readonly RegisterSiteAction $registerSite,
    ) {}

    public function create(): Response
    {
        return Inertia::render('Brochure/Register');
    }

    public function store(RegisterTeamRequest $request): RedirectResponse
    {
        $this->authorize('create', Site::class);

        $result = $this->registerSite->execute($request->validated());

        Auth::login($result['user']);

        $protocol = $request->secure() ? 'https' : 'http';
        $domain   = config('app.domain');

        return redirect()->away("{$protocol}://{$result['site']->slug}.{$domain}/manage/users");
    }
}
