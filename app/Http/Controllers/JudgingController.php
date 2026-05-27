<?php

namespace App\Http\Controllers;

use App\Models\EntryScore;
use App\Models\Event;
use App\Models\ProjectEntry;
use App\Models\ScoringRubric;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class JudgingController extends Controller
{
    public function dashboard(Request $request, Event $event): View
    {
        abort_unless($event->owner_id === $request->user()->id || $request->user()->hasPermission('events.manage'), 403);

        return view('manage.events.judging', [
            'event' => $event->load('entries.team', 'rubrics', 'judgeAssignments'),
            'rankings' => $event->entries()->with('team', 'scores')->get()->sortByDesc(fn ($entry) => $entry->totalScore())->values(),
        ]);
    }

    public function assignRubric(Request $request, Event $event): RedirectResponse
    {
        abort_unless($event->owner_id === $request->user()->id || $request->user()->hasPermission('events.manage'), 403);

        $attributes = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'max_score' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $event->rubrics()->create($attributes);

        return back()->with('status', 'Rubric added.');
    }

    public function assignJudge(Request $request, Event $event): RedirectResponse
    {
        abort_unless($event->owner_id === $request->user()->id || $request->user()->hasPermission('events.manage'), 403);

        $attributes = $request->validate([
            'judge_id' => ['required', 'exists:users,id'],
        ]);

        $event->judgeAssignments()->firstOrCreate($attributes);

        return back()->with('status', 'Judge assigned.');
    }

    public function review(ProjectEntry $entry): View
    {
        return view('judging.review', [
            'entry' => $entry->load('event.rubrics', 'team', 'scores'),
        ]);
    }

    public function score(Request $request, ProjectEntry $entry): RedirectResponse
    {
        abort_unless(
            $entry->event->judgeAssignments()->where('judge_id', $request->user()->id)->exists()
                || $request->user()->hasPermission('events.manage'),
            403,
        );

        $attributes = $request->validate([
            'scoring_rubric_id' => ['required', 'exists:scoring_rubrics,id'],
            'score' => ['required', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $rubric = ScoringRubric::query()->findOrFail($attributes['scoring_rubric_id']);
        abort_unless($rubric->event_id === $entry->event_id && $attributes['score'] <= $rubric->max_score, 422);

        EntryScore::query()->updateOrCreate(
            [
                'project_entry_id' => $entry->id,
                'scoring_rubric_id' => $rubric->id,
                'judge_id' => $request->user()->id,
            ],
            $attributes,
        );

        return back()->with('status', 'Score saved.');
    }

    public function finalize(Request $request, Event $event): RedirectResponse
    {
        abort_unless($event->owner_id === $request->user()->id || $request->user()->hasPermission('events.manage'), 403);

        $event->update(['judging_finalized_at' => now()]);

        return back()->with('status', 'Judging finalized.');
    }
}
