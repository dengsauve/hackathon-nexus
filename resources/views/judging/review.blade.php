<x-layouts.app title="Review {{ $entry->title }}">
    <section class="mx-auto max-w-4xl px-5 py-10">
        <h1 class="text-4xl font-semibold text-white">{{ $entry->title }}</h1>
        <p class="mt-3 text-zinc-300">{{ $entry->team->name }} · {{ $entry->event->name }}</p>
        <form method="POST" action="{{ route('judging.score', $entry) }}" class="mt-8 space-y-4 rounded-lg border border-white/10 bg-white/5 p-5">
            @csrf
            <select name="scoring_rubric_id" class="w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white">
                @foreach ($entry->event->rubrics as $rubric)
                    <option value="{{ $rubric->id }}">{{ $rubric->name }} / {{ $rubric->max_score }}</option>
                @endforeach
            </select>
            <input name="score" type="number" min="0" required class="w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white">
            <textarea name="notes" rows="4" placeholder="Notes" class="w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white"></textarea>
            <button class="rounded-md bg-teal-300 px-5 py-3 font-semibold text-zinc-950">Save score</button>
        </form>
    </section>
</x-layouts.app>
