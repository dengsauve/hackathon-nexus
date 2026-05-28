<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ManagedEventController extends Controller
{
    public function index(Request $request): View
    {
        $events = Event::query()
            ->where('owner_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('manage.events.index', [
            'events' => $events,
        ]);
    }

    public function create(Request $request): View
    {
        return view('manage.events.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $attributes = $this->validateEvent($request);

        $event = Event::query()->create([
            ...$attributes,
            'owner_id' => $request->user()->id,
            'slug' => $this->uniqueSlug($attributes['name']),
        ]);

        $this->generateQrCode($event);

        return redirect()->route('manage.events.show', $event);
    }

    public function show(Request $request, Event $event): View
    {
        $this->authorizeEventManagement($request, $event);

        return view('manage.events.show', [
            'event' => $event->load(['teams', 'assistanceRequests.team', 'assistanceRequests.requester']),
        ]);
    }

    public function edit(Request $request, Event $event): View
    {
        $this->authorizeEventManagement($request, $event);

        return view('manage.events.edit', [
            'event' => $event,
        ]);
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $this->authorizeEventManagement($request, $event);

        $attributes = $this->validateEvent($request);

        $event->update([
            ...$attributes,
            'slug' => $event->name === $attributes['name'] ? $event->slug : $this->uniqueSlug($attributes['name'], $event),
        ]);

        $this->generateQrCode($event);

        return redirect()->route('manage.events.show', $event)->with('status', 'Event updated.');
    }

    public function publish(Request $request, Event $event): RedirectResponse
    {
        $this->authorizeEventManagement($request, $event);

        $event->update(['status' => 'published']);

        return back()->with('status', 'Event published.');
    }

    public function unpublish(Request $request, Event $event): RedirectResponse
    {
        $this->authorizeEventManagement($request, $event);

        $event->update(['status' => 'draft']);

        return back()->with('status', 'Event unpublished.');
    }

    public function start(Request $request, Event $event): RedirectResponse
    {
        $this->authorizeEventManagement($request, $event);

        $event->update(['status' => 'live']);

        return back()->with('status', 'Event started.');
    }

    public function end(Request $request, Event $event): RedirectResponse
    {
        $this->authorizeEventManagement($request, $event);

        $event->update(['status' => 'ended']);

        return back()->with('status', 'Event ended.');
    }

    private function validateEvent(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'location' => ['required', 'string', 'max:255'],
            'format' => ['required', Rule::in(Event::FORMATS)],
            'status' => ['required', Rule::in(Event::LIFECYCLE_STATUSES)],
            'visibility' => ['required', Rule::in(Event::VISIBILITIES)],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'registration_closes_at' => ['nullable', 'date', 'before_or_equal:starts_at'],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:100000'],
        ]);
    }

    private function authorizeEventManagement(Request $request, Event $event): void
    {
        abort_unless($event->owner_id === $request->user()->id || $request->user()->hasPermission('events.manage'), 403);
    }

    private function uniqueSlug(string $name, ?Event $ignore = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $counter = 2;

        while (Event::query()
            ->where('slug', $slug)
            ->when($ignore, fn ($query) => $query->whereKeyNot($ignore->id))
            ->exists()) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }

    private function generateQrCode(Event $event): void
    {
        File::ensureDirectoryExists(public_path('qrcodes'));

        $relativePath = 'qrcodes/events/'.$event->slug.'.svg';
        File::ensureDirectoryExists(dirname(public_path($relativePath)));

        $builder = new Builder(
            writer: new SvgWriter,
            writerOptions: [],
            validateResult: false,
            data: $event->publicUrl(),
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 320,
            margin: 16,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
        );

        $builder->build()->saveToFile(public_path($relativePath));

        $event->forceFill(['qr_code_path' => $relativePath])->save();
    }
}
