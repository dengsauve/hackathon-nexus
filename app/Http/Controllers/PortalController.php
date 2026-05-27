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

        $joinedEvents = $user->events()
            ->orderBy('starts_at')
            ->limit(3)
            ->get();

        $recommendedEvents = Event::query()
            ->publiclyDiscoverable()
            ->whereNotIn('id', $joinedEvents->pluck('id'))
            ->orderBy('starts_at')
            ->limit(3)
            ->get();

        return view('portal', [
            'joinedEvents' => $joinedEvents,
            'managedTeams' => $user->managedTeams()->orderBy('name')->get(),
            'joinedTeams' => $user->teams()->orderBy('name')->get(),
            'recommendedEvents' => $recommendedEvents,
        ]);
    }
}
