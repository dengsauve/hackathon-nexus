<x-layouts.app title="Manage events - Hackathon Nexus">
    <section class="mx-auto max-w-6xl px-5 py-10">
        <div class="flex items-end justify-between gap-4 border-b border-white/10 pb-8">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-teal-200">Organizer</p>
                <h1 class="mt-3 text-4xl font-semibold text-white">Manage events</h1>
            </div>
            <a href="{{ route('manage.events.create') }}" class="rounded-md bg-teal-300 px-4 py-3 text-sm font-semibold text-zinc-950 hover:bg-teal-200">Create event</a>
        </div>

        <div class="mt-8 space-y-3">
            @forelse ($events as $event)
                <a href="{{ route('manage.events.show', $event) }}" class="block rounded-lg border border-white/10 bg-white/5 p-5 hover:border-teal-300/50">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h2 class="text-xl font-semibold text-white">{{ $event->name }}</h2>
                        <span class="text-sm text-zinc-400">{{ $event->status }} · {{ $event->visibility }}</span>
                    </div>
                    <p class="mt-2 text-sm text-zinc-400">{{ $event->summary }}</p>
                </a>
            @empty
                <div class="rounded-lg border border-dashed border-white/15 bg-white/5 p-6 text-zinc-400">No managed events yet.</div>
            @endforelse
        </div>

        <div class="mt-8">{{ $events->links() }}</div>
    </section>
</x-layouts.app>
