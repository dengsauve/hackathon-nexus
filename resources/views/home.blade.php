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
</x-layouts.app>
