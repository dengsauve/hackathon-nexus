<x-layouts.app title="Manage {{ $event->name }} - Hackathon Nexus">
    <section class="mx-auto max-w-6xl px-5 py-10">
        <div class="flex flex-col justify-between gap-4 border-b border-white/10 pb-8 md:flex-row md:items-end">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-teal-200">Event management</p>
                <h1 class="mt-3 text-4xl font-semibold text-white">{{ $event->name }}</h1>
                <p class="mt-3 text-zinc-300">{{ $event->summary }}</p>
            </div>
            <a href="{{ route('manage.events.edit', $event) }}" class="rounded-md bg-teal-300 px-4 py-3 text-sm font-semibold text-zinc-950 hover:bg-teal-200">Edit event</a>
        </div>

        <div class="grid gap-5 py-8 md:grid-cols-4">
            @foreach (['Status' => $event->status, 'Visibility' => $event->visibility, 'Teams' => $event->teams->count(), 'Requests' => $event->assistanceRequests->count()] as $label => $value)
                <div class="rounded-lg border border-white/10 bg-white/5 p-5">
                    <p class="text-sm text-zinc-400">{{ $label }}</p>
                    <p class="mt-2 text-2xl font-semibold text-white">{{ $value }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid gap-6 lg:grid-cols-[1fr_20rem]">
            <section class="rounded-lg border border-white/10 bg-white/5 p-5">
                <div class="flex flex-wrap gap-3">
                    <form method="POST" action="{{ route('manage.events.publish', $event) }}">@csrf<button class="rounded-md border border-white/15 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">Publish</button></form>
                    <form method="POST" action="{{ route('manage.events.unpublish', $event) }}">@csrf<button class="rounded-md border border-white/15 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">Unpublish</button></form>
                    <form method="POST" action="{{ route('manage.events.start', $event) }}">@csrf<button class="rounded-md border border-white/15 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">Start</button></form>
                    <form method="POST" action="{{ route('manage.events.end', $event) }}">@csrf<button class="rounded-md border border-white/15 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">End</button></form>
                </div>

                <h2 class="mt-8 text-xl font-semibold text-white">Registered teams</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($event->teams as $team)
                        <div class="rounded-md bg-zinc-950/70 p-4">
                            <p class="font-semibold text-white">{{ $team->name }}</p>
                            <p class="mt-1 text-sm text-zinc-400">{{ $team->pivot->status }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-zinc-400">No teams have registered yet.</p>
                    @endforelse
                </div>
            </section>

            <aside class="rounded-lg border border-white/10 bg-white/5 p-5">
                <h2 class="text-xl font-semibold text-white">Public URL</h2>
                <a href="{{ route('events.show', $event) }}" class="mt-3 block break-all text-sm text-teal-200 hover:text-teal-100">{{ route('events.show', $event) }}</a>
                @if ($event->qr_code_path)
                    <img src="{{ asset($event->qr_code_path) }}" alt="" class="mt-5 rounded-md bg-white p-3">
                @endif
            </aside>
        </div>

        <section class="mt-6 rounded-lg border border-white/10 bg-white/5 p-5">
            <h2 class="text-xl font-semibold text-white">Assistance requests</h2>
            <div class="mt-5 space-y-3">
                @forelse ($event->assistanceRequests as $request)
                    <div class="rounded-md bg-zinc-950/70 p-4">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <p class="font-semibold text-white">{{ $request->subject }}</p>
                                <p class="mt-1 text-sm text-zinc-400">{{ $request->message }}</p>
                                <p class="mt-2 text-xs text-zinc-500">{{ $request->team?->name ?? 'Personal request' }} · {{ $request->created_at->diffForHumans() }}</p>
                            </div>
                            <form method="POST" action="{{ route('assistance-requests.update', $request) }}" class="flex gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="rounded-md border border-white/10 bg-zinc-950 px-3 py-2 text-sm text-white">
                                    @foreach ([\App\Models\AssistanceRequest::OPEN, \App\Models\AssistanceRequest::IN_PROGRESS, \App\Models\AssistanceRequest::RESOLVED] as $status)
                                        <option value="{{ $status }}" @selected($request->status === $status)>{{ $status }}</option>
                                    @endforeach
                                </select>
                                <button class="rounded-md bg-teal-300 px-3 py-2 text-sm font-semibold text-zinc-950">Update</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-zinc-400">No assistance requests yet.</p>
                @endforelse
            </div>
        </section>
    </section>
</x-layouts.app>
