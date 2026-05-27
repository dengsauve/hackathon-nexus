<x-layouts.app title="Edit {{ $entry->title }} - Hackathon Nexus">
    <section class="mx-auto max-w-4xl px-5 py-10">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h1 class="text-4xl font-semibold text-white">Edit entry</h1>
                <p class="mt-3 text-zinc-300">{{ $entry->team->name }} for {{ $entry->event->name }}</p>
            </div>
            @if ($entry->status === \App\Models\ProjectEntry::DRAFT)
                <form method="POST" action="{{ route('entries.submit', $entry) }}">
                    @csrf
                    <button class="rounded-md bg-teal-300 px-4 py-3 text-sm font-semibold text-zinc-950 hover:bg-teal-200" data-confirm="Submit this entry?">Submit</button>
                </form>
            @endif
        </div>
        <form method="POST" enctype="multipart/form-data" action="{{ route('entries.update', $entry) }}" class="mt-8 rounded-lg border border-white/10 bg-white/5 p-5">
            @method('PUT')
            @include('entries.form', ['button' => 'Save entry', 'entry' => $entry])
        </form>
        <section class="mt-6 rounded-lg border border-white/10 bg-white/5 p-5">
            <h2 class="text-xl font-semibold text-white">Assets</h2>
            <div class="mt-4 space-y-2">
                @forelse ($entry->assets as $asset)
                    <p class="rounded-md bg-zinc-950/70 px-4 py-3 text-sm text-zinc-300">{{ $asset->original_name }} · {{ number_format($asset->size / 1024, 1) }} KB</p>
                @empty
                    <p class="text-sm text-zinc-400">No assets uploaded yet.</p>
                @endforelse
            </div>
        </section>
    </section>
</x-layouts.app>
