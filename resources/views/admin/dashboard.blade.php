<x-layouts.app title="Admin - Hackathon Nexus">
    <section class="mx-auto max-w-6xl px-5 py-10">
        <h1 class="text-4xl font-semibold text-white">Admin dashboard</h1>
        <div class="mt-8 grid gap-5 md:grid-cols-2">
            <div class="rounded-lg border border-white/10 bg-white/5 p-5"><p class="text-sm text-zinc-400">Users</p><p class="mt-2 text-3xl font-semibold text-white">{{ $userCount }}</p></div>
            <div class="rounded-lg border border-white/10 bg-white/5 p-5"><p class="text-sm text-zinc-400">Events</p><p class="mt-2 text-3xl font-semibold text-white">{{ $eventCount }}</p></div>
        </div>
        <section class="mt-8 rounded-lg border border-white/10 bg-white/5 p-5">
            <h2 class="text-xl font-semibold text-white">Event moderation</h2>
            <div class="mt-4 space-y-3">
                @foreach ($draftEvents as $event)
                    <form method="POST" action="{{ route('admin.events.moderate', $event) }}" class="flex items-center gap-3 rounded-md bg-zinc-950/70 p-3">
                        @csrf
                        @method('PATCH')
                        <span class="min-w-0 flex-1 text-zinc-200">{{ $event->name }}</span>
                        <select name="visibility" class="rounded-md bg-zinc-950 px-3 py-2 text-sm text-white">
                            @foreach (\App\Models\Event::VISIBILITIES as $visibility)
                                <option value="{{ $visibility }}" @selected($event->visibility === $visibility)>{{ $visibility }}</option>
                            @endforeach
                        </select>
                        <button class="rounded-md bg-teal-300 px-3 py-2 text-sm font-semibold text-zinc-950">Save</button>
                    </form>
                @endforeach
            </div>
        </section>
        <section class="mt-8 rounded-lg border border-white/10 bg-white/5 p-5">
            <h2 class="text-xl font-semibold text-white">User moderation</h2>
            <div class="mt-4 space-y-3">
                @foreach ($users as $user)
                    <form method="POST" action="{{ route('admin.users.moderate', $user) }}" class="flex items-center gap-3 rounded-md bg-zinc-950/70 p-3">
                        @csrf
                        @method('PATCH')
                        <span class="min-w-0 flex-1 text-zinc-200">{{ $user->email }}</span>
                        <select name="status" class="rounded-md bg-zinc-950 px-3 py-2 text-sm text-white">
                            <option value="active" @selected($user->status === 'active')>active</option>
                            <option value="suspended" @selected($user->status === 'suspended')>suspended</option>
                        </select>
                        <button class="rounded-md bg-teal-300 px-3 py-2 text-sm font-semibold text-zinc-950">Save</button>
                    </form>
                @endforeach
            </div>
        </section>
        <section class="mt-8 rounded-lg border border-white/10 bg-white/5 p-5">
            <h2 class="text-xl font-semibold text-white">Audit logs</h2>
            <div class="mt-4 space-y-2">
                @forelse ($auditLogs as $log)
                    <p class="rounded-md bg-zinc-950/70 px-4 py-3 text-sm text-zinc-300">{{ $log->action }} · {{ $log->created_at->diffForHumans() }}</p>
                @empty
                    <p class="text-sm text-zinc-400">No audit logs yet.</p>
                @endforelse
            </div>
        </section>
    </section>
</x-layouts.app>
