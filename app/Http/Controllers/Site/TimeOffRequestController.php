<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Actions\TimeOffRequests\RequestTimeOffAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTimeOffRequestRequest;
use App\Models\Site;
use Illuminate\Http\RedirectResponse;

class TimeOffRequestController extends Controller
{
    public function __construct(
        private readonly RequestTimeOffAction $requestTimeOff,
    ) {}

    public function store(StoreTimeOffRequestRequest $request): RedirectResponse
    {
        /** @var Site $site */
        $site = resolve('currentSite');
        $user = $request->user();
        $team = $user->primaryTeamFor($site);

        abort_unless($team !== null, 403);

        $this->requestTimeOff->execute(
            $site,
            $team,
            $user,
            $request->validated('date'),
            $request->validated('start_time'),
            $request->validated('end_time'),
            $request->validated('notes'),
        );

        $message = $user->requiresTimeOffApproval($site)
            ? 'Time off request submitted for approval.'
            : 'Time off recorded.';

        return redirect('/?date='.$request->validated('date'))
            ->with('message', $message);
    }
}
