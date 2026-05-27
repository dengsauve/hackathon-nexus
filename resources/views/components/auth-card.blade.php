@props(['title', 'subtitle'])

<section class="mx-auto grid min-h-[calc(100vh-73px)] max-w-6xl items-center px-5 py-12">
    <div class="mx-auto w-full max-w-md rounded-lg border border-white/10 bg-zinc-900/80 p-6 shadow-2xl shadow-black/30 backdrop-blur">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-white">{{ $title }}</h1>
            <p class="mt-2 text-sm leading-6 text-zinc-400">{{ $subtitle }}</p>
        </div>

        @if ($errors->any())
            <div class="mb-5 rounded-md border border-rose-400/30 bg-rose-500/10 px-4 py-3 text-sm text-rose-100" data-alert>
                <div class="flex items-start justify-between gap-4">
                    <p>{{ $errors->first() }}</p>
                    <button type="button" class="text-rose-100/80 hover:text-white" data-dismiss="[data-alert]" aria-label="Dismiss">&times;</button>
                </div>
            </div>
        @endif

        {{ $slot }}
    </div>
</section>
