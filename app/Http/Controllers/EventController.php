<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $events = Event::query()
            ->publiclyDiscoverable()
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search')->toString();

                $query->where(function ($query) use ($search): void {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('summary', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('format'), fn ($query) => $query->where('format', $request->string('format')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->orderBy('starts_at')
            ->paginate(6)
            ->withQueryString();

        return view('events.index', [
            'events' => $events,
            'formats' => Event::FORMATS,
            'statuses' => Event::PUBLIC_STATUSES,
        ]);
    }

    public function show(Event $event): View
    {
        abort_unless($event->isPubliclyViewable(), 404);

        return view('events.show', [
            'event' => $event,
        ]);
    }
}
