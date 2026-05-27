<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Notifications\TeamInvitationNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TeamInvitationController extends Controller
{
    public function store(Request $request, Team $team): RedirectResponse
    {
        abort_unless($team->isManagedBy($request->user()), 403);

        $attributes = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', Rule::in(['member', 'manager'])],
            'github_handle' => ['nullable', 'string', 'max:255'],
        ]);

        $duplicatePending = $team->invitations()
            ->where('email', $attributes['email'])
            ->where('status', TeamInvitation::PENDING)
            ->where('expires_at', '>', now())
            ->exists();

        if ($duplicatePending) {
            return back()->withErrors(['email' => 'That email already has a pending invitation for this team.']);
        }

        $invitation = $team->invitations()->create([
            ...$attributes,
            'invited_by' => $request->user()->id,
            'token' => Str::random(48),
            'status' => TeamInvitation::PENDING,
            'expires_at' => now()->addDays(7),
        ]);

        $invitation->notify(new TeamInvitationNotification($invitation));

        return redirect()->route('teams.show', $team)->with('status', 'Invitation sent.');
    }

    public function show(string $token): View
    {
        return view('teams.invitations.show', [
            'invitation' => TeamInvitation::query()->where('token', $token)->firstOrFail(),
        ]);
    }

    public function accept(Request $request, string $token): RedirectResponse
    {
        $invitation = TeamInvitation::query()->where('token', $token)->firstOrFail();

        if (! $invitation->isPending()) {
            return redirect()->route('login')->withErrors(['email' => 'This invitation is no longer available.']);
        }

        $user = User::query()->where('email', $invitation->email)->first();

        if ($user) {
            $invitation->team->members()->syncWithoutDetaching([
                $user->id => ['role' => $invitation->role],
            ]);
        }

        $invitation->update([
            'status' => TeamInvitation::ACCEPTED,
            'responded_at' => now(),
        ]);

        if ($user) {
            auth()->login($user);

            return redirect()->route('teams.show', $invitation->team)->with('status', 'Invitation accepted.');
        }

        return redirect()->route('register')->with('status', 'Invitation accepted. Create an account with '.$invitation->email.' to join the team.');
    }

    public function decline(string $token): RedirectResponse
    {
        $invitation = TeamInvitation::query()->where('token', $token)->firstOrFail();

        if ($invitation->isPending()) {
            $invitation->update([
                'status' => TeamInvitation::DECLINED,
                'responded_at' => now(),
            ]);
        }

        return redirect()->route('home')->with('status', 'Invitation declined.');
    }
}
