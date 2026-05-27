<x-layouts.app title="Notification preferences">
    <section class="mx-auto max-w-xl px-5 py-10">
        <h1 class="text-4xl font-semibold text-white">Notification preferences</h1>
        <form method="POST" action="{{ route('notifications.update') }}" class="mt-8 space-y-4 rounded-lg border border-white/10 bg-white/5 p-5">
            @csrf
            @method('PUT')
            @foreach (['event_reminders' => 'Event reminders', 'submission_confirmations' => 'Submission confirmations', 'assistance_updates' => 'Assistance updates'] as $key => $label)
                <label class="flex items-center gap-3 text-zinc-200">
                    <input type="checkbox" name="{{ $key }}" value="1" @checked($preferences[$key] ?? true)>
                    {{ $label }}
                </label>
            @endforeach
            <button class="rounded-md bg-teal-300 px-5 py-3 font-semibold text-zinc-950">Save preferences</button>
        </form>
    </section>
</x-layouts.app>
