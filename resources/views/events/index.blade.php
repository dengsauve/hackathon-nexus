<x-layouts.app title="Events - Hackathon Nexus">
    <section class="mx-auto max-w-6xl px-5 py-10">
        <div class="flex flex-col justify-between gap-5 border-b border-white/10 pb-8 md:flex-row md:items-end">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-teal-200">Discovery</p>
                <h1 class="mt-3 text-4xl font-semibold text-white">Browse events</h1>
                <p class="mt-3 max-w-2xl text-zinc-300">Search public hackathons, filter by format or state, and open the event detail page before signing in.</p>
            </div>
        </div>

        <form method="GET" action="{{ route('events.index') }}" class="mt-8 grid gap-3 rounded-lg border border-white/10 bg-white/5 p-4 md:grid-cols-[1fr_12rem_12rem_auto]">
            <input name="search" value="{{ request('search') }}" placeholder="Search events, summaries, locations" class="rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">
            <select name="format" class="rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">
                <option value="">All formats</option>
                @foreach ($formats as $format)
                    <option value="{{ $format }}" @selected(request('format') === $format)>{{ ucfirst($format) }}</option>
                @endforeach
            </select>
            <select name="status" class="rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">
                <option value="">All states</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <button class="rounded-md bg-teal-300 px-5 py-3 font-semibold text-zinc-950 hover:bg-teal-200">Search</button>
        </form>

        <div class="mt-8 grid gap-5 md:grid-cols-2">
            @forelse ($events as $event)
                <a href="{{ route('events.show', $event) }}" class="rounded-lg border border-white/10 bg-white/5 p-5 hover:border-teal-300/50 hover:bg-white/10">
                    <div class="flex flex-wrap items-center gap-2 text-xs font-semibold uppercase tracking-wide">
                        <span class="rounded-full bg-teal-300/15 px-2.5 py-1 text-teal-100">{{ $event->format }}</span>
                        <span class="rounded-full bg-white/10 px-2.5 py-1 text-zinc-300">{{ $event->status }}</span>
                        <span class="ml-auto text-zinc-400">{{ $event->starts_at->format('M j, Y') }}</span>
                    </div>
                    <h2 class="mt-4 text-2xl font-semibold text-white">{{ $event->name }}</h2>
                    <p class="mt-3 text-sm leading-6 text-zinc-400">{{ $event->summary }}</p>
                    <div class="mt-5 flex flex-wrap gap-4 text-sm text-zinc-300">
                        <span>{{ $event->location }}</span>
                        @if ($event->capacity)
                            <span>{{ $event->capacity }} spots</span>
                        @endif
                    </div>
                </a>
            @empty
                <div class="rounded-lg border border-white/10 bg-white/5 p-6 text-zinc-300 md:col-span-2">
                    No public events match those filters.
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $events->links() }}
        </div>
    </section>
</x-layouts.app>
