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

    public function index(Request $request, Site $site): Response
    {
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
                'startTime' => $timeOffRequest->timeBlock?->start_time,
                'endTime'   => $timeOffRequest->timeBlock?->end_time,
                'notes'     => $timeOffRequest->notes,
            ]);

        return Inertia::render('Admin/TimeOffRequests/Index', [
            'requests'    => $requests,
            'isSiteAdmin' => $user->isSiteAdminFor($site),
        ]);
    }

    public function approve(Request $request, Site $site): RedirectResponse
    {
        $timeOffRequest = $this->resolvePendingRequest($request, $site);
        $this->authorize('approve', $timeOffRequest);

        try {
            /** @var User $reviewer */
            $reviewer = $request->user();
            $this->approveTimeOffRequest->execute($timeOffRequest, $reviewer);
        } catch (ValidationException $validationException) {
            $message = collect($validationException->errors())->flatten()->first() ?? 'Availability rule violated';

            return redirect()->route('manage.time-off-requests.index')->with('error', $message);
        }

        return redirect()->route('manage.time-off-requests.index')->with('message', 'Request approved.');
    }

    public function reject(Request $request, Site $site): RedirectResponse
    {
        $timeOffRequest = $this->resolvePendingRequest($request, $site);
        $this->authorize('reject', $timeOffRequest);

        /** @var User $reviewer */
        $reviewer = $request->user();
        $this->rejectTimeOffRequest->execute($timeOffRequest, $reviewer);

        return redirect()->route('manage.time-off-requests.index')->with('message', 'Request rejected.');
    }

    protected function resolvePendingRequest(Request $request, Site $site): TimeOffRequest
    {
        /** @var User $user */
        $user = $request->user();

        return TimeOffRequest::query()
            ->whereIn('team_id', $user->approverTeamIdsFor($site))
            ->where('status', TimeOffRequestStatus::Pending)
            ->findOrFail((string) $request->route('timeOffRequest'));
    }
}
