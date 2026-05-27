<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EventTeamRegistrationController extends Controller
{
    public function store(Request $request, Event $event): RedirectResponse
    {
        $attributes = $request->validate([
            'team_id' => ['required', 'exists:teams,id'],
        ]);

        $team = $request->user()->managedTeams()->whereKey($attributes['team_id'])->firstOrFail();

        if (! $event->isPubliclyViewable()) {
            throw ValidationException::withMessages(['team_id' => 'This event is not open for team registration.']);
        }

        if ($event->registration_closes_at && $event->registration_closes_at->isPast()) {
            throw ValidationException::withMessages(['team_id' => 'Registration has closed for this event.']);
        }

        if ($event->capacity && $event->teams()->count() >= $event->capacity) {
            throw ValidationException::withMessages(['team_id' => 'This event is already at capacity.']);
        }

        if ($event->teams()->whereKey($team->id)->exists()) {
            throw ValidationException::withMessages(['team_id' => 'This team is already registered for the event.']);
        }

        $event->teams()->attach($team->id, [
            'registered_by' => $request->user()->id,
            'status' => 'registered',
        ]);

        return redirect()->route('events.show', $event)->with('status', 'Team registered for event.');
    }
}
