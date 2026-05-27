<x-layouts.app title="Portal - Hackathon Nexus">
    <section class="mx-auto max-w-6xl px-5 py-10">
        <div class="flex flex-col justify-between gap-5 border-b border-white/10 pb-8 md:flex-row md:items-end">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-teal-200">Portal</p>
                <h1 class="mt-3 text-4xl font-semibold text-white">Welcome, {{ auth()->user()->name }}</h1>
                <p class="mt-3 max-w-2xl text-zinc-300">Your private workspace is ready for project activity, team coordination, and event updates.</p>
            </div>
            @if (auth()->user()->avatar_url)
                <img src="{{ auth()->user()->avatar_url }}" alt="" class="h-16 w-16 rounded-full border border-white/20 object-cover">
            @endif
        </div>

        <div class="grid gap-5 py-8 md:grid-cols-3">
            <div class="rounded-lg border border-white/10 bg-white/5 p-5">
                <p class="text-sm text-zinc-400">Next checkpoint</p>
                <p class="mt-3 text-2xl font-semibold text-white">Demo draft</p>
                <p class="mt-2 text-sm text-zinc-400">Today, 4:00 PM</p>
            </div>
            <div class="rounded-lg border border-white/10 bg-white/5 p-5">
                <p class="text-sm text-zinc-400">Team status</p>
                <p class="mt-3 text-2xl font-semibold text-white">Active</p>
                <p class="mt-2 text-sm text-zinc-400">Profile and matching hooks can land here next.</p>
            </div>
            <div class="rounded-lg border border-white/10 bg-white/5 p-5">
                <p class="text-sm text-zinc-400">Account</p>
                <p class="mt-3 text-2xl font-semibold text-white">{{ auth()->user()->email }}</p>
                <p class="mt-2 text-sm text-zinc-400">Signed in with session auth.</p>
            </div>
        </div>
    </section>
</x-layouts.app>
