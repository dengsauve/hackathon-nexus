<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $registeredTeamEvents = $user->teams()
            ->with(['events' => fn ($query) => $query->orderBy('starts_at')])
            ->get()
            ->flatMap(fn ($team) => $team->events->map(fn ($event) => [
                'team' => $team,
                'event' => $event,
            ]))
            ->sortBy(fn ($registration) => $registration['event']->starts_at)
            ->values();

        $recommendedEvents = Event::query()
            ->publiclyDiscoverable()
            ->whereNotIn('id', $registeredTeamEvents->pluck('event.id')->unique())
            ->orderBy('starts_at')
            ->limit(3)
            ->get();

        return view('portal', [
            'managedTeams' => $user->managedTeams()->orderBy('name')->get(),
            'joinedTeams' => $user->teams()->orderBy('name')->get(),
            'registeredTeamEvents' => $registeredTeamEvents,
            'recommendedEvents' => $recommendedEvents,
        ]);
    }
}
