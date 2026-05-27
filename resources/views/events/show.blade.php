<x-layouts.app title="{{ $event->name }} - Hackathon Nexus">
    <section class="mx-auto max-w-6xl px-5 py-10">
        <a href="{{ route('events.index') }}" class="text-sm font-semibold text-teal-200 hover:text-teal-100">Back to events</a>

        <div class="mt-6 grid gap-8 lg:grid-cols-[1fr_22rem]">
            <article>
                <div class="flex flex-wrap gap-2 text-xs font-semibold uppercase tracking-wide">
                    <span class="rounded-full bg-teal-300/15 px-2.5 py-1 text-teal-100">{{ $event->format }}</span>
                    <span class="rounded-full bg-white/10 px-2.5 py-1 text-zinc-300">{{ $event->status }}</span>
                    <span class="rounded-full bg-white/10 px-2.5 py-1 text-zinc-300">{{ $event->visibility }}</span>
                </div>
                <h1 class="mt-5 text-4xl font-semibold leading-tight text-white md:text-5xl">{{ $event->name }}</h1>
                <p class="mt-5 max-w-3xl text-lg leading-8 text-zinc-300">{{ $event->summary }}</p>
                <div class="mt-8 border-t border-white/10 pt-8 text-sm leading-7 text-zinc-300">
                    {{ $event->description }}
                </div>
            </article>

            <aside class="rounded-lg border border-white/10 bg-white/5 p-5">
                <h2 class="text-lg font-semibold text-white">Event details</h2>
                <dl class="mt-5 space-y-4 text-sm">
                    <div>
                        <dt class="text-zinc-500">Starts</dt>
                        <dd class="mt-1 text-zinc-200">{{ $event->starts_at->format('M j, Y g:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-zinc-500">Ends</dt>
                        <dd class="mt-1 text-zinc-200">{{ $event->ends_at->format('M j, Y g:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-zinc-500">Location</dt>
                        <dd class="mt-1 text-zinc-200">{{ $event->location }}</dd>
                    </div>
                    @if ($event->registration_closes_at)
                        <div>
                            <dt class="text-zinc-500">Registration closes</dt>
                            <dd class="mt-1 text-zinc-200">{{ $event->registration_closes_at->format('M j, Y') }}</dd>
                        </div>
                    @endif
                    @if ($event->capacity)
                        <div>
                            <dt class="text-zinc-500">Capacity</dt>
                            <dd class="mt-1 text-zinc-200">{{ $event->capacity }} participants</dd>
                        </div>
                    @endif
                </dl>

                @auth
                    <a href="{{ route('portal') }}" class="mt-6 block rounded-md bg-teal-300 px-4 py-3 text-center font-semibold text-zinc-950 hover:bg-teal-200">Open portal</a>
                @else
                    <a href="{{ route('register') }}" class="mt-6 block rounded-md bg-teal-300 px-4 py-3 text-center font-semibold text-zinc-950 hover:bg-teal-200">Create account</a>
                @endauth
            </aside>
        </div>
    </section>
</x-layouts.app>
