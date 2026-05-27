<?php

namespace App\Http\Controllers;

use App\Models\AssistanceRequest;
use App\Models\Event;
use App\Notifications\AssistanceRequestNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AssistanceRequestController extends Controller
{
    public function store(Request $request, Event $event): RedirectResponse
    {
        $attributes = $request->validate([
            'team_id' => ['nullable', 'exists:teams,id'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        if (isset($attributes['team_id'])) {
            $teamIsRegistered = $event->teams()->whereKey($attributes['team_id'])->exists();
            abort_unless($teamIsRegistered, 403);
        }

        $assistanceRequest = $event->assistanceRequests()->create([
            ...$attributes,
            'requested_by' => $request->user()->id,
            'status' => AssistanceRequest::OPEN,
        ]);

        $event->owner?->notify(new AssistanceRequestNotification($assistanceRequest));

        return back()->with('status', 'Assistance request submitted.');
    }

    public function update(Request $request, AssistanceRequest $assistanceRequest): RedirectResponse
    {
        abort_unless(
            $assistanceRequest->event->owner_id === $request->user()->id || $request->user()->hasPermission('events.manage'),
            403,
        );

        $attributes = $request->validate([
            'status' => ['required', Rule::in([AssistanceRequest::OPEN, AssistanceRequest::IN_PROGRESS, AssistanceRequest::RESOLVED])],
        ]);

        $assistanceRequest->update([
            ...$attributes,
            'responded_by' => $request->user()->id,
            'responded_at' => $attributes['status'] === AssistanceRequest::RESOLVED ? now() : $assistanceRequest->responded_at,
        ]);

        return back()->with('status', 'Assistance request updated.');
    }
}
