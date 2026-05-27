<x-layouts.app title="Create entry - Hackathon Nexus">
    <section class="mx-auto max-w-4xl px-5 py-10">
        <h1 class="text-4xl font-semibold text-white">Create entry</h1>
        <p class="mt-3 text-zinc-300">{{ $team->name }} for {{ $event->name }}</p>
        <form method="POST" enctype="multipart/form-data" action="{{ route('entries.store', [$team, $event]) }}" class="mt-8 rounded-lg border border-white/10 bg-white/5 p-5">
            @include('entries.form', ['button' => 'Create entry'])
        </form>
    </section>
</x-layouts.app>
