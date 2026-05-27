<x-layouts.app title="Team invitation - Hackathon Nexus">
    <section class="mx-auto max-w-xl px-5 py-16">
        <div class="rounded-lg border border-white/10 bg-white/5 p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-teal-200">Invitation</p>
            <h1 class="mt-3 text-3xl font-semibold text-white">{{ $invitation->team->name }}</h1>
            <p class="mt-3 text-zinc-300">{{ $invitation->email }} was invited as {{ $invitation->role }}.</p>
            <p class="mt-2 text-sm text-zinc-400">Status: {{ $invitation->status }}</p>
            @if ($invitation->isPending())
                <div class="mt-6 grid gap-3 sm:grid-cols-2">
                    <form method="POST" action="{{ route('team-invitations.accept', $invitation->token) }}">
                        @csrf
                        <button class="w-full rounded-md bg-teal-300 px-4 py-3 font-semibold text-zinc-950 hover:bg-teal-200">Accept</button>
                    </form>
                    <form method="POST" action="{{ route('team-invitations.decline', $invitation->token) }}">
                        @csrf
                        <button class="w-full rounded-md border border-white/15 px-4 py-3 font-semibold text-white hover:bg-white/10">Decline</button>
                    </form>
                </div>
            @endif
        </div>
    </section>
</x-layouts.app>
