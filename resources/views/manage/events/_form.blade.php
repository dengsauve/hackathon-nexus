@csrf
<div class="grid gap-5 md:grid-cols-2">
    <label class="block md:col-span-2">
        <span class="text-sm font-medium text-zinc-200">Name</span>
        <input name="name" required value="{{ old('name', $event->name ?? '') }}" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">
    </label>
    <label class="block md:col-span-2">
        <span class="text-sm font-medium text-zinc-200">Summary</span>
        <input name="summary" required value="{{ old('summary', $event->summary ?? '') }}" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">
    </label>
    <label class="block md:col-span-2">
        <span class="text-sm font-medium text-zinc-200">Description</span>
        <textarea name="description" required rows="5" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">{{ old('description', $event->description ?? '') }}</textarea>
    </label>
    <label class="block">
        <span class="text-sm font-medium text-zinc-200">Location</span>
        <input name="location" required value="{{ old('location', $event->location ?? '') }}" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">
    </label>
    <label class="block">
        <span class="text-sm font-medium text-zinc-200">Format</span>
        <select name="format" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">
            @foreach (\App\Models\Event::FORMATS as $format)
                <option value="{{ $format }}" @selected(old('format', $event->format ?? 'in-person') === $format)>{{ ucfirst($format) }}</option>
            @endforeach
        </select>
    </label>
    <label class="block">
        <span class="text-sm font-medium text-zinc-200">Status</span>
        <select name="status" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">
            @foreach (\App\Models\Event::LIFECYCLE_STATUSES as $status)
                <option value="{{ $status }}" @selected(old('status', $event->status ?? 'draft') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </label>
    <label class="block">
        <span class="text-sm font-medium text-zinc-200">Visibility</span>
        <select name="visibility" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">
            @foreach (\App\Models\Event::VISIBILITIES as $visibility)
                <option value="{{ $visibility }}" @selected(old('visibility', $event->visibility ?? 'private') === $visibility)>{{ ucfirst($visibility) }}</option>
            @endforeach
        </select>
    </label>
    <label class="block">
        <span class="text-sm font-medium text-zinc-200">Starts at</span>
        <input name="starts_at" type="datetime-local" required value="{{ old('starts_at', isset($event) ? $event->starts_at->format('Y-m-d\TH:i') : '') }}" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">
    </label>
    <label class="block">
        <span class="text-sm font-medium text-zinc-200">Ends at</span>
        <input name="ends_at" type="datetime-local" required value="{{ old('ends_at', isset($event) ? $event->ends_at->format('Y-m-d\TH:i') : '') }}" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">
    </label>
    <label class="block">
        <span class="text-sm font-medium text-zinc-200">Registration closes</span>
        <input name="registration_closes_at" type="datetime-local" value="{{ old('registration_closes_at', isset($event) && $event->registration_closes_at ? $event->registration_closes_at->format('Y-m-d\TH:i') : '') }}" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">
    </label>
    <label class="block">
        <span class="text-sm font-medium text-zinc-200">Capacity</span>
        <input name="capacity" type="number" min="1" value="{{ old('capacity', $event->capacity ?? '') }}" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">
    </label>
</div>
<button class="mt-6 rounded-md bg-teal-300 px-5 py-3 font-semibold text-zinc-950 hover:bg-teal-200">{{ $button }}</button>
