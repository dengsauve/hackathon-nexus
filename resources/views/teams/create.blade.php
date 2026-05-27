<x-layouts.app title="Create team - Hackathon Nexus">
    <section class="mx-auto max-w-3xl px-5 py-10">
        <h1 class="text-4xl font-semibold text-white">Create team</h1>
        <form method="POST" action="{{ route('teams.store') }}" class="mt-8 space-y-5 rounded-lg border border-white/10 bg-white/5 p-5">
            @csrf
            <label class="block">
                <span class="text-sm font-medium text-zinc-200">Name</span>
                <input name="name" required value="{{ old('name') }}" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">
            </label>
            <label class="block">
                <span class="text-sm font-medium text-zinc-200">Description</span>
                <textarea name="description" rows="4" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">{{ old('description') }}</textarea>
            </label>
            <button class="rounded-md bg-teal-300 px-5 py-3 font-semibold text-zinc-950 hover:bg-teal-200">Create team</button>
        </form>
    </section>
</x-layouts.app>
