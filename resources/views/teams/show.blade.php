<x-layouts.app title="{{ $team->name }} - Hackathon Nexus">
    <section class="mx-auto max-w-6xl px-5 py-10">
        <div class="flex flex-col justify-between gap-4 border-b border-white/10 pb-8 md:flex-row md:items-end">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-teal-200">Team</p>
                <h1 class="mt-3 text-4xl font-semibold text-white">{{ $team->name }}</h1>
                <p class="mt-3 max-w-2xl text-zinc-300">{{ $team->description }}</p>
            </div>
            @if ($team->isManagedBy(auth()->user()))
                <a href="{{ route('teams.edit', $team) }}" class="rounded-md bg-teal-300 px-4 py-3 text-sm font-semibold text-zinc-950 hover:bg-teal-200">Edit team</a>
            @endif
        </div>

        <div class="grid gap-6 py-8 lg:grid-cols-2">
            <section class="rounded-lg border border-white/10 bg-white/5 p-5">
                <h2 class="text-xl font-semibold text-white">Members</h2>
                <div class="mt-5 space-y-3">
                    @foreach ($team->members as $member)
                        <div class="flex items-center justify-between rounded-md bg-zinc-950/70 px-4 py-3">
                            <span class="text-zinc-200">{{ $member->name }}</span>
                            <span class="text-sm text-zinc-400">{{ $member->pivot->role }}</span>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="rounded-lg border border-white/10 bg-white/5 p-5">
                <h2 class="text-xl font-semibold text-white">Invite member</h2>
                @if ($team->isManagedBy(auth()->user()))
                    <form method="POST" action="{{ route('teams.invitations.store', $team) }}" class="mt-5 space-y-3">
                        @csrf
                        <input name="email" type="email" required placeholder="Email" class="w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">
                        <select name="role" class="w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">
                            <option value="member">Member</option>
                            <option value="manager">Manager</option>
                        </select>
                        <input name="github_handle" placeholder="GitHub handle roadmap placeholder" class="w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">
                        <button class="rounded-md bg-teal-300 px-4 py-3 text-sm font-semibold text-zinc-950 hover:bg-teal-200">Send invitation</button>
                    </form>
                @else
                    <p class="mt-5 text-sm text-zinc-400">Only team managers can invite members.</p>
                @endif

                <h3 class="mt-8 font-semibold text-white">Invitations</h3>
                <div class="mt-3 space-y-2">
                    @forelse ($team->invitations as $invitation)
                        <div class="rounded-md bg-zinc-950/70 px-4 py-3 text-sm text-zinc-300">
                            {{ $invitation->email }} · {{ $invitation->role }} · {{ $invitation->status }}
                        </div>
                    @empty
                        <p class="text-sm text-zinc-400">No invitations yet.</p>
                    @endforelse
                </div>
            </section>
        </div>

        <section class="rounded-lg border border-white/10 bg-white/5 p-5">
            <h2 class="text-xl font-semibold text-white">Registered events</h2>
            <div class="mt-5 grid gap-3 md:grid-cols-2">
                @forelse ($team->events as $event)
                    <a href="{{ route('events.show', $event) }}" class="rounded-md bg-zinc-950/70 p-4 hover:bg-zinc-900">
                        <span class="font-semibold text-white">{{ $event->name }}</span>
                        <span class="mt-1 block text-sm text-zinc-400">{{ $event->starts_at->format('M j, Y') }}</span>
                    </a>
                @empty
                    <p class="text-sm text-zinc-400">This team has not joined any events yet.</p>
                @endforelse
            </div>
        </section>
    </section>
</x-layouts.app>
