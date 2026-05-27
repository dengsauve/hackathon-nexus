<x-layouts.app title="Hackathon Nexus">
    <section class="mx-auto grid max-w-6xl gap-10 px-5 py-16 lg:grid-cols-[1.1fr_0.9fr] lg:items-center lg:py-24">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-teal-200">Build, join, ship</p>
            <h1 class="mt-5 max-w-3xl text-5xl font-semibold leading-tight text-white md:text-6xl">
                A focused portal for hackathon teams and project momentum.
            </h1>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-zinc-300">
                Start with email, Google, or GitHub auth, then give participants a private workspace for team updates, event details, and next actions.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                @auth
                    <a href="{{ route('portal') }}" class="rounded-md bg-teal-300 px-5 py-3 font-semibold text-zinc-950 hover:bg-teal-200">Open portal</a>
                @else
                    <a href="{{ route('register') }}" class="rounded-md bg-teal-300 px-5 py-3 font-semibold text-zinc-950 hover:bg-teal-200">Get started</a>
                    <a href="{{ route('login') }}" class="rounded-md border border-white/15 px-5 py-3 font-semibold text-white hover:bg-white/10">Log in</a>
                @endauth
                <a href="{{ route('events.index') }}" class="rounded-md border border-white/15 px-5 py-3 font-semibold text-white hover:bg-white/10">Browse events</a>
            </div>
        </div>

        <div class="rounded-lg border border-white/10 bg-zinc-900/70 p-5 shadow-2xl shadow-black/30 backdrop-blur">
            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <div>
                    <p class="text-sm text-zinc-400">Current sprint</p>
                    <p class="mt-1 text-xl font-semibold text-white" data-cycle-value="Idea validation|Prototype review|Demo readiness">Idea validation</p>
                </div>
                <span class="rounded-full bg-teal-300 px-3 py-1 text-xs font-bold uppercase tracking-wide text-zinc-950">Live</span>
            </div>
            <div class="grid gap-3 py-5 sm:grid-cols-3">
                <div class="rounded-md bg-white/5 p-4">
                    <p class="text-3xl font-semibold text-white">12</p>
                    <p class="mt-1 text-sm text-zinc-400">Teams</p>
                </div>
                <div class="rounded-md bg-white/5 p-4">
                    <p class="text-3xl font-semibold text-white">36</p>
                    <p class="mt-1 text-sm text-zinc-400">Builders</p>
                </div>
                <div class="rounded-md bg-white/5 p-4">
                    <p class="text-3xl font-semibold text-white">8</p>
                    <p class="mt-1 text-sm text-zinc-400">Mentors</p>
                </div>
            </div>
            <div class="space-y-3 text-sm text-zinc-300">
                <div class="flex items-center justify-between rounded-md bg-white/5 px-4 py-3">
                    <span>Registration</span>
                    <span class="font-medium text-teal-200">Open</span>
                </div>
                <div class="flex items-center justify-between rounded-md bg-white/5 px-4 py-3">
                    <span>OAuth providers</span>
                    <span class="font-medium text-teal-200">Google + GitHub</span>
                </div>
                <div class="flex items-center justify-between rounded-md bg-white/5 px-4 py-3">
                    <span>Frontend</span>
                    <span class="font-medium text-teal-200">Vite + Tailwind</span>
                </div>
            </div>
        </div>
    </section>

    <section class="border-t border-white/10 bg-zinc-950/50">
        <div class="mx-auto max-w-6xl px-5 py-14">
            <div class="flex flex-col justify-between gap-4 md:flex-row md:items-end">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-teal-200">Upcoming events</p>
                    <h2 class="mt-3 text-3xl font-semibold text-white">Find a hackathon to join</h2>
                </div>
                <a href="{{ route('events.index') }}" class="text-sm font-semibold text-teal-200 hover:text-teal-100">View all events</a>
            </div>

            <div class="mt-8 grid gap-5 md:grid-cols-3">
                @forelse ($upcomingEvents as $event)
                    <a href="{{ route('events.show', $event) }}" class="rounded-lg border border-white/10 bg-white/5 p-5 hover:border-teal-300/50 hover:bg-white/10">
                        <div class="flex items-center justify-between gap-3 text-xs font-semibold uppercase tracking-wide">
                            <span class="text-teal-200">{{ $event->format }}</span>
                            <span class="text-zinc-400">{{ $event->starts_at->format('M j') }}</span>
                        </div>
                        <h3 class="mt-4 text-xl font-semibold text-white">{{ $event->name }}</h3>
                        <p class="mt-3 text-sm leading-6 text-zinc-400">{{ $event->summary }}</p>
                        <p class="mt-5 text-sm font-medium text-zinc-300">{{ $event->location }}</p>
                    </a>
                @empty
                    <div class="rounded-lg border border-white/10 bg-white/5 p-5 text-sm text-zinc-400 md:col-span-3">
                        No public events are available yet.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="border-t border-white/10">
        <div class="mx-auto grid max-w-6xl gap-6 px-5 py-14 md:grid-cols-3">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-teal-200">About</p>
                <h2 class="mt-3 text-3xl font-semibold text-white">Built for organizers and builders</h2>
            </div>
            <p class="text-sm leading-6 text-zinc-300">Organizers can publish events, route teams into the right workflows, and keep project submissions moving.</p>
            <p class="text-sm leading-6 text-zinc-300">Participants can discover events before logging in, then use the portal for team and event activity once authenticated.</p>
        </div>
    </section>
</x-layouts.app>
