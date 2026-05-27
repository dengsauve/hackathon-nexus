<x-layouts.app title="Create event - Hackathon Nexus">
    <section class="mx-auto max-w-4xl px-5 py-10">
        <h1 class="text-4xl font-semibold text-white">Create event</h1>
        <form method="POST" action="{{ route('manage.events.store') }}" class="mt-8 rounded-lg border border-white/10 bg-white/5 p-5">
            @include('manage.events._form', ['button' => 'Create event'])
        </form>
    </section>
</x-layouts.app>
