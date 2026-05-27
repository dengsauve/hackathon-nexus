<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificationPreferenceController extends Controller
{
    public function edit(Request $request): View
    {
        return view('notifications.preferences', [
            'preferences' => $request->user()->notification_preferences ?? [],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $preferences = $request->validate([
            'event_reminders' => ['nullable', 'boolean'],
            'submission_confirmations' => ['nullable', 'boolean'],
            'assistance_updates' => ['nullable', 'boolean'],
        ]);

        $request->user()->update([
            'notification_preferences' => [
                'event_reminders' => (bool) ($preferences['event_reminders'] ?? false),
                'submission_confirmations' => (bool) ($preferences['submission_confirmations'] ?? false),
                'assistance_updates' => (bool) ($preferences['assistance_updates'] ?? false),
            ],
        ]);

        return back()->with('status', 'Notification preferences updated.');
    }
}
