<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\TimeOffRequests\ApproveTimeOffRequestAction;
use App\Actions\TimeOffRequests\RejectTimeOffRequestAction;
use App\Enums\TimeOffRequestStatus;
use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\TimeOffRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class TimeOffRequestController extends Controller
{
    public function __construct(
        private readonly ApproveTimeOffRequestAction $approveTimeOffRequest,
        private readonly RejectTimeOffRequestAction $rejectTimeOffRequest,
    ) {}

    public function index(Request $request): Response
    {
        /** @var Site $site */
        $site = resolve('currentSite');
        /** @var User $user */
        $user = $request->user();

        $requests = TimeOffRequest::query()
            ->whereIn('team_id', $user->approverTeamIdsFor($site))
            ->where('status', TimeOffRequestStatus::Pending)
            ->with(['user', 'team', 'timeBlock'])->oldest()
            ->get()
            ->map(fn (TimeOffRequest $timeOffRequest): array => [
                'id'        => $timeOffRequest->id,
                'userName'  => $timeOffRequest->user->name,
                'teamName'  => $timeOffRequest->team->name,
                'date'      => $timeOffRequest->timeBlock?->date->toDateString(),
                'startTime' => $timeOffRequest->timeBlock
                    ? mb_substr((string) $timeOffRequest->timeBlock->getRawOriginal('start_time'), 0, 5)
                    : null,
                'endTime' => $timeOffRequest->timeBlock
                    ? mb_substr((string) $timeOffRequest->timeBlock->getRawOriginal('end_time'), 0, 5)
                    : null,
                'notes' => $timeOffRequest->notes,
            ]);

        return Inertia::render('Admin/TimeOffRequests/Index', [
            'requests'    => $requests,
            'isSiteAdmin' => $user->isSiteAdminFor($site),
        ]);
    }

    public function approve(Request $request): RedirectResponse
    {
        $timeOffRequest = $this->resolvePendingRequestFromRoute($request);

        $this->authorizeApproval($request->user(), $timeOffRequest);

        try {
            $this->approveTimeOffRequest->execute($timeOffRequest, auth()->user());
        } catch (ValidationException $validationException) {
            $message = collect($validationException->errors())->flatten()->first() ?? 'Availability rule violated';

            return redirect('/manage/time-off-requests')->with('error', $message);
        }

        return redirect('/manage/time-off-requests')->with('message', 'Request approved.');
    }

    public function reject(Request $request): RedirectResponse
    {
        $timeOffRequest = $this->resolvePendingRequestFromRoute($request);

        $this->authorizeApproval($request->user(), $timeOffRequest);

        $this->rejectTimeOffRequest->execute($timeOffRequest, auth()->user());

        return redirect('/manage/time-off-requests')->with('message', 'Request rejected.');
    }

    protected function resolvePendingRequestFromRoute(Request $request): TimeOffRequest
    {
        /** @var Site $site */
        $site = resolve('currentSite');
        /** @var User $user */
        $user = $request->user();

        return TimeOffRequest::query()
            ->whereIn('team_id', $user->approverTeamIdsFor($site))
            ->where('status', TimeOffRequestStatus::Pending)
            ->findOrFail((string) $request->route('timeOffRequest'));
    }

    protected function authorizeApproval(?User $user, TimeOffRequest $timeOffRequest): void
    {
        abort_unless(
            $user instanceof User && $user->canApproveTimeOffFor($timeOffRequest->team),
            403,
        );
    }
}
