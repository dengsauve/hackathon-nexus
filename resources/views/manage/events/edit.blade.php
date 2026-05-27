<x-layouts.app title="Edit {{ $event->name }} - Hackathon Nexus">
    <section class="mx-auto max-w-4xl px-5 py-10">
        <h1 class="text-4xl font-semibold text-white">Edit event</h1>
        <form method="POST" action="{{ route('manage.events.update', $event) }}" class="mt-8 rounded-lg border border-white/10 bg-white/5 p-5">
            @method('PUT')
            @include('manage.events._form', ['button' => 'Save event'])
        </form>
    </section>
</x-layouts.app>
