<x-layouts.app title="Hackathon Nexus">
    <section class="mx-auto grid max-w-6xl gap-10 px-5 py-16 lg:grid-cols-[1.1fr_0.9fr] lg:items-center lg:py-24">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-teal-200" aria-label="Join, Build, Win">
                <span data-typewriter-words="Join In|Build Anything|Hack Everything|Win|Play the Game">Join</span><span class="typewriter-cursor" aria-hidden="true"></span>
            </p>
            <h1 class="mt-5 max-w-3xl text-5xl font-semibold leading-tight text-white md:text-6xl">
                The first of it's kind Hackathon Platform
            </h1>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-zinc-300">
                Discover in-person or online hackathons, create a team, invite your friends. Want to host your own? We've got your back!
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
        <div class="mx-auto max-w-6xl px-5 py-14">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-teal-200">About</p>
                <h2 class="mt-3 text-3xl font-semibold text-white">Built for organizers and builders</h2>
                <p class="mt-4 text-sm leading-6 text-zinc-300">Hackathon Nexus brings the core event workflow into one place, from launch through final judging.</p>
            </div>

            <div class="mt-12 divide-y divide-white/10">
                <article class="grid gap-6 py-10 md:grid-cols-[0.8fr_1.2fr] md:items-center">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-teal-200">01 / Launch</p>
                        <h3 class="mt-4 max-w-xl text-4xl font-semibold leading-tight text-white">Host events with registration built in.</h3>
                    </div>
                    <div class="md:border-l md:border-teal-300/30 md:pl-8">
                        <p class="text-lg leading-8 text-zinc-200">Create and publish event pages, generate QR codes for fast sharing and check-in, manage team registrations, and keep schedules, capacity, and visibility under control from the organizer dashboard.</p>
                        <p class="mt-5 text-sm font-semibold uppercase tracking-[0.18em] text-zinc-500">Event pages / QR codes / Team registration</p>
                    </div>
                </article>

                <article class="grid gap-6 py-10 md:grid-cols-[0.8fr_1.2fr] md:items-center">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-rose-200">02 / Build</p>
                        <h3 class="mt-4 max-w-xl text-4xl font-semibold leading-tight text-white">Give teams one place to submit their work.</h3>
                    </div>
                    <div class="md:border-l md:border-rose-300/30 md:pl-8">
                        <p class="text-lg leading-8 text-zinc-200">Teams can register, organize their entry, share GitHub or GitLab repositories, attach supporting assets, and keep project details ready for mentors, organizers, and judges.</p>
                        <p class="mt-5 text-sm font-semibold uppercase tracking-[0.18em] text-zinc-500">Repositories / Assets / Entries</p>
                    </div>
                </article>

                <article class="grid gap-6 py-10 md:grid-cols-[0.8fr_1.2fr] md:items-center">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-amber-200">03 / Run</p>
                        <h3 class="mt-4 max-w-xl text-4xl font-semibold leading-tight text-white">Keep the room moving while the hackathon is live.</h3>
                    </div>
                    <div class="md:border-l md:border-amber-300/30 md:pl-8">
                        <p class="text-lg leading-8 text-zinc-200">Track what is happening in the moment, respond to live participant support requests, and keep teams aligned with event announcements, operational updates, and organizer actions.</p>
                        <p class="mt-5 text-sm font-semibold uppercase tracking-[0.18em] text-zinc-500">Live support / Announcements / Operations</p>
                    </div>
                </article>

                <article class="grid gap-6 py-10 md:grid-cols-[0.8fr_1.2fr] md:items-center">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-teal-200">04 / Judge</p>
                        <h3 class="mt-4 max-w-xl text-4xl font-semibold leading-tight text-white">Finish with judging in the same system.</h3>
                    </div>
                    <div class="md:border-l md:border-teal-300/30 md:pl-8">
                        <p class="text-lg leading-8 text-zinc-200">At the end, Hackathon Nexus becomes a one stop shop for reviewing every contestant, assigning judges, scoring rubrics, comparing submissions, and finalizing placements.</p>
                        <p class="mt-5 text-sm font-semibold uppercase tracking-[0.18em] text-zinc-500">Rubrics / Reviewers / Results</p>
                    </div>
                </article>
            </div>
        </div>
    </section>
</x-layouts.app>
