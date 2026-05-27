<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\ProjectEntry;
use App\Models\Team;
use App\Notifications\SubmissionConfirmationNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProjectEntryController extends Controller
{
    public function create(Request $request, Team $team, Event $event): View
    {
        abort_unless($team->isManagedBy($request->user()) && $event->teams()->whereKey($team->id)->exists(), 403);

        return view('entries.create', compact('team', 'event'));
    }

    public function store(Request $request, Team $team, Event $event): RedirectResponse
    {
        abort_unless($team->isManagedBy($request->user()) && $event->teams()->whereKey($team->id)->exists(), 403);

        $entry = ProjectEntry::query()->create([
            ...$this->validated($request),
            'event_id' => $event->id,
            'team_id' => $team->id,
            'created_by' => $request->user()->id,
            'status' => ProjectEntry::DRAFT,
        ]);

        $this->storeAssets($request, $entry);

        return redirect()->route('entries.edit', $entry);
    }

    public function edit(Request $request, ProjectEntry $entry): View
    {
        abort_unless($entry->team->isManagedBy($request->user()), 403);

        return view('entries.edit', [
            'entry' => $entry->load('assets', 'event', 'team'),
        ]);
    }

    public function update(Request $request, ProjectEntry $entry): RedirectResponse
    {
        abort_unless($entry->team->isManagedBy($request->user()), 403);
        abort_if($entry->status === ProjectEntry::SUBMITTED, 403);

        $entry->update($this->validated($request));
        $this->storeAssets($request, $entry);

        return back()->with('status', 'Entry updated.');
    }

    public function submit(Request $request, ProjectEntry $entry): RedirectResponse
    {
        abort_unless($entry->team->isManagedBy($request->user()), 403);
        abort_if($entry->event->ends_at->isPast(), 403);

        $entry->update([
            'status' => ProjectEntry::SUBMITTED,
            'submitted_at' => now(),
        ]);

        $request->user()->notify(new SubmissionConfirmationNotification($entry));

        return redirect()->route('portal')->with('status', 'Entry submitted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'idea' => ['required', 'string', 'max:2000'],
            'description' => ['required', 'string', 'max:5000'],
            'goal_statement' => ['required', 'string', 'max:2000'],
            'github_repository' => ['nullable', 'url', 'max:255'],
            'gitlab_repository' => ['nullable', 'url', 'max:255'],
            'assets.*' => ['nullable', 'file', 'max:10240', 'mimetypes:image/png,image/jpeg,image/webp,application/pdf,text/plain'],
        ]);
    }

    private function storeAssets(Request $request, ProjectEntry $entry): void
    {
        foreach ($request->file('assets', []) as $file) {
            $path = $file->store('entry-assets');
            $entry->assets()->create([
                'uploaded_by' => $request->user()->id,
                'disk' => config('filesystems.default'),
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType() ?: 'application/octet-stream',
                'size' => $file->getSize(),
            ]);
        }
    }
}
