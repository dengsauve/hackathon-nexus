<x-layouts.app title="Judging - {{ $event->name }}">
    <section class="mx-auto max-w-6xl px-5 py-10">
        <h1 class="text-4xl font-semibold text-white">Judging: {{ $event->name }}</h1>
        <form method="POST" action="{{ route('manage.events.judging.finalize', $event) }}" class="mt-5">
            @csrf
            <button class="rounded-md bg-teal-300 px-4 py-3 text-sm font-semibold text-zinc-950 hover:bg-teal-200" data-confirm="Finalize judging results?">Finalize judging</button>
            @if ($event->judging_finalized_at)
                <span class="ml-3 text-sm text-zinc-400">Finalized {{ $event->judging_finalized_at->diffForHumans() }}</span>
            @endif
        </form>
        <div class="mt-8 grid gap-6 lg:grid-cols-2">
            <section class="rounded-lg border border-white/10 bg-white/5 p-5">
                <h2 class="text-xl font-semibold text-white">Rubrics</h2>
                <form method="POST" action="{{ route('manage.events.rubrics.store', $event) }}" class="mt-4 flex gap-3">
                    @csrf
                    <input name="name" required placeholder="Rubric name" class="min-w-0 flex-1 rounded-md border border-white/10 bg-zinc-950 px-3 py-2 text-white">
                    <input name="max_score" type="number" min="1" max="100" value="10" class="w-24 rounded-md border border-white/10 bg-zinc-950 px-3 py-2 text-white">
                    <button class="rounded-md bg-teal-300 px-4 py-2 font-semibold text-zinc-950">Add</button>
                </form>
                <div class="mt-4 space-y-2">
                    @foreach ($event->rubrics as $rubric)
                        <p class="rounded-md bg-zinc-950/70 px-4 py-3 text-sm text-zinc-300">{{ $rubric->name }} / {{ $rubric->max_score }}</p>
                    @endforeach
                </div>
            </section>
            <section class="rounded-lg border border-white/10 bg-white/5 p-5">
                <h2 class="text-xl font-semibold text-white">Rankings</h2>
                <div class="mt-4 space-y-2">
                    @forelse ($rankings as $entry)
                        <a href="{{ route('judging.review', $entry) }}" class="block rounded-md bg-zinc-950/70 px-4 py-3 text-sm text-zinc-300">{{ $loop->iteration }}. {{ $entry->title }} · {{ $entry->totalScore() }} pts</a>
                    @empty
                        <p class="text-sm text-zinc-400">No submitted entries yet.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </section>
</x-layouts.app>
