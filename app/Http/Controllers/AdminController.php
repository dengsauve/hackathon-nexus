<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Event;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(Request $request): View
    {
        abort_unless($request->user()->hasPermission('admin.access'), 403);

        return view('admin.dashboard', [
            'eventCount' => Event::query()->count(),
            'userCount' => User::query()->count(),
            'users' => User::query()->latest()->limit(20)->get(),
            'draftEvents' => Event::query()->where('status', 'draft')->latest()->limit(10)->get(),
            'auditLogs' => AuditLog::query()->latest()->limit(20)->get(),
        ]);
    }

    public function moderateEvent(Request $request, Event $event): RedirectResponse
    {
        abort_unless($request->user()->hasPermission('admin.access'), 403);

        $attributes = $request->validate([
            'visibility' => ['required', 'in:public,unlisted,private'],
        ]);

        $event->update($attributes);

        AuditLog::query()->create([
            'user_id' => $request->user()->id,
            'action' => 'event.moderated',
            'auditable_type' => Event::class,
            'auditable_id' => $event->id,
            'metadata' => $attributes,
        ]);

        return back()->with('status', 'Event moderated.');
    }

    public function moderateUser(Request $request, User $user): RedirectResponse
    {
        abort_unless($request->user()->hasPermission('admin.access'), 403);

        $attributes = $request->validate([
            'status' => ['required', 'in:active,suspended'],
        ]);

        $user->update($attributes);

        AuditLog::query()->create([
            'user_id' => $request->user()->id,
            'action' => 'user.moderated',
            'auditable_type' => User::class,
            'auditable_id' => $user->id,
            'metadata' => $attributes,
        ]);

        return back()->with('status', 'User moderated.');
    }
}
