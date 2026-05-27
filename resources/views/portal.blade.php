<x-layouts.app title="Portal - Hackathon Nexus">
    <section class="mx-auto max-w-6xl px-5 py-10">
        <div class="flex flex-col justify-between gap-5 border-b border-white/10 pb-8 md:flex-row md:items-end">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-teal-200">Portal</p>
                <h1 class="mt-3 text-4xl font-semibold text-white">Welcome, {{ auth()->user()->name }}</h1>
                <p class="mt-3 max-w-2xl text-zinc-300">Your private workspace for event activity, team coordination, and next actions.</p>
            </div>
            @if (auth()->user()->avatar_url)
                <img src="{{ auth()->user()->avatar_url }}" alt="" class="h-16 w-16 rounded-full border border-white/20 object-cover">
            @endif
        </div>

        <div class="grid gap-5 py-8 md:grid-cols-3">
            <div class="rounded-lg border border-white/10 bg-white/5 p-5">
                <p class="text-sm text-zinc-400">Joined events</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ $registeredTeamEvents->count() }}</p>
                <p class="mt-2 text-sm text-zinc-400">Active team registrations</p>
            </div>
            <div class="rounded-lg border border-white/10 bg-white/5 p-5">
                <p class="text-sm text-zinc-400">Managed teams</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ $managedTeams->count() }}</p>
                <p class="mt-2 text-sm text-zinc-400">Teams you own or organize</p>
            </div>
            <div class="rounded-lg border border-white/10 bg-white/5 p-5">
                <p class="text-sm text-zinc-400">Joined teams</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ $joinedTeams->count() }}</p>
                <p class="mt-2 text-sm text-zinc-400">Team memberships</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1.15fr_0.85fr]">
            <section class="rounded-lg border border-white/10 bg-white/5 p-5">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-white">Upcoming joined events</h2>
                        <p class="mt-1 text-sm text-zinc-400">Events your teams are currently participating in.</p>
                    </div>
                    <a href="{{ route('events.index') }}" class="text-sm font-semibold text-teal-200 hover:text-teal-100">Browse</a>
                </div>

                <div class="mt-5 space-y-3">
                    @forelse ($registeredTeamEvents as $registration)
                        <a href="{{ route('events.show', $registration['event']) }}" class="block rounded-md border border-white/10 bg-zinc-950/70 p-4 hover:border-teal-300/50">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <h3 class="font-semibold text-white">{{ $registration['event']->name }}</h3>
                                <span class="text-sm text-teal-200">{{ $registration['event']->starts_at->format('M j') }}</span>
                            </div>
                            <p class="mt-2 text-sm leading-6 text-zinc-400">{{ $registration['event']->summary }}</p>
                            <p class="mt-3 text-sm text-zinc-300">{{ $registration['team']->name }} · {{ $registration['event']->location }} · {{ ucfirst($registration['event']->format) }}</p>
                        </a>
                    @empty
                        <div class="rounded-md border border-dashed border-white/15 bg-zinc-950/50 p-5">
                            <p class="font-semibold text-white">No joined events yet</p>
                            <p class="mt-2 text-sm leading-6 text-zinc-400">Create or manage a team, then register it for an event when registration opens.</p>
                            <a href="{{ route('events.index') }}" class="mt-4 inline-flex rounded-md bg-teal-300 px-4 py-2 text-sm font-semibold text-zinc-950 hover:bg-teal-200">Browse events</a>
                        </div>
                    @endforelse
                </div>
            </section>

            <section class="rounded-lg border border-white/10 bg-white/5 p-5">
                <h2 class="text-xl font-semibold text-white">Quick actions</h2>
                <div class="mt-5 grid gap-3">
                    <a href="{{ route('events.index') }}" class="rounded-md bg-teal-300 px-4 py-3 text-sm font-semibold text-zinc-950 hover:bg-teal-200">Browse events</a>
                    <a href="{{ route('home') }}" class="rounded-md border border-white/15 px-4 py-3 text-sm font-semibold text-white hover:bg-white/10">View homepage</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-full rounded-md border border-white/15 px-4 py-3 text-sm font-semibold text-white hover:bg-white/10">Log out</button>
                    </form>
                </div>

                <div class="mt-6 border-t border-white/10 pt-5">
                    <h3 class="font-semibold text-white">Recommended events</h3>
                    <div class="mt-3 space-y-3">
                        @forelse ($recommendedEvents as $event)
                            <a href="{{ route('events.show', $event) }}" class="block rounded-md bg-zinc-950/60 p-3 text-sm text-zinc-300 hover:bg-zinc-900">
                                <span class="font-medium text-white">{{ $event->name }}</span>
                                <span class="mt-1 block text-zinc-500">{{ $event->starts_at->format('M j, Y') }}</span>
                            </a>
                        @empty
                            <p class="text-sm leading-6 text-zinc-400">No additional public events are available right now.</p>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>

        <div class="grid gap-6 py-8 lg:grid-cols-2">
            <section class="rounded-lg border border-white/10 bg-white/5 p-5">
                <h2 class="text-xl font-semibold text-white">Managed teams</h2>
                <div class="mt-5 space-y-3">
                    @forelse ($managedTeams as $team)
                        <div class="rounded-md border border-white/10 bg-zinc-950/70 p-4">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <h3 class="font-semibold text-white">{{ $team->name }}</h3>
                                    <span class="mt-1 block text-xs font-semibold uppercase tracking-wide text-teal-200">Manager</span>
                                </div>
                                <a href="{{ route('teams.show', $team) }}" class="rounded-md bg-teal-300 px-3 py-2 text-sm font-semibold text-zinc-950 hover:bg-teal-200">Manage</a>
                            </div>
                            <p class="mt-2 text-sm leading-6 text-zinc-400">{{ $team->description }}</p>
                        </div>
                    @empty
                        <div class="rounded-md border border-dashed border-white/15 bg-zinc-950/50 p-5 text-sm leading-6 text-zinc-400">
                            You are not managing any teams yet. Team creation arrives in the next phase.
                        </div>
                    @endforelse
                </div>
            </section>

            <section class="rounded-lg border border-white/10 bg-white/5 p-5">
                <h2 class="text-xl font-semibold text-white">Joined teams</h2>
                <div class="mt-5 space-y-3">
                    @forelse ($joinedTeams as $team)
                        <div class="rounded-md border border-white/10 bg-zinc-950/70 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <h3 class="font-semibold text-white">{{ $team->name }}</h3>
                                <span class="text-xs font-semibold uppercase tracking-wide text-zinc-400">{{ $team->pivot->role }}</span>
                            </div>
                            <p class="mt-2 text-sm leading-6 text-zinc-400">{{ $team->description }}</p>
                        </div>
                    @empty
                        <div class="rounded-md border border-dashed border-white/15 bg-zinc-950/50 p-5 text-sm leading-6 text-zinc-400">
                            You have not joined a team yet. Invitations and team joining are planned for the team system phase.
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </section>
</x-layouts.app>
