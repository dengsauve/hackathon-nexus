<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TeamController extends Controller
{
    public function create(): View
    {
        return view('teams.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $attributes = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $team = Team::query()->create([
            ...$attributes,
            'owner_id' => $request->user()->id,
            'slug' => $this->uniqueSlug($attributes['name']),
        ]);

        $team->members()->attach($request->user()->id, ['role' => 'owner']);

        return redirect()->route('teams.show', $team);
    }

    public function show(Team $team): View
    {
        return view('teams.show', [
            'team' => $team->load(['members', 'invitations' => fn ($query) => $query->latest(), 'events']),
        ]);
    }

    public function edit(Request $request, Team $team): View
    {
        abort_unless($team->isManagedBy($request->user()), 403);

        return view('teams.edit', [
            'team' => $team,
        ]);
    }

    public function update(Request $request, Team $team): RedirectResponse
    {
        abort_unless($team->isManagedBy($request->user()), 403);

        $attributes = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', Rule::in(['active', 'archived'])],
        ]);

        $team->update([
            ...$attributes,
            'slug' => $team->name === $attributes['name'] ? $team->slug : $this->uniqueSlug($attributes['name'], $team),
            'archived_at' => $attributes['status'] === 'archived' ? now() : null,
        ]);

        return redirect()->route('teams.show', $team)->with('status', 'Team updated.');
    }

    public function destroy(Request $request, Team $team): RedirectResponse
    {
        abort_unless($team->owner_id === $request->user()->id, 403);

        $team->update([
            'status' => 'archived',
            'archived_at' => now(),
        ]);

        return redirect()->route('portal')->with('status', 'Team archived.');
    }

    private function uniqueSlug(string $name, ?Team $ignore = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $counter = 2;

        while (Team::query()
            ->where('slug', $slug)
            ->when($ignore, fn ($query) => $query->whereKeyNot($ignore->id))
            ->exists()) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }
}
