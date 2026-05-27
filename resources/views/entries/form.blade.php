@csrf
<div class="grid gap-5">
    <label class="block">
        <span class="text-sm font-medium text-zinc-200">Title</span>
        <input name="title" required value="{{ old('title', $entry->title ?? '') }}" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">
    </label>
    <label class="block">
        <span class="text-sm font-medium text-zinc-200">Idea</span>
        <textarea name="idea" required rows="3" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">{{ old('idea', $entry->idea ?? '') }}</textarea>
    </label>
    <label class="block">
        <span class="text-sm font-medium text-zinc-200">Description</span>
        <textarea name="description" required rows="5" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">{{ old('description', $entry->description ?? '') }}</textarea>
    </label>
    <label class="block">
        <span class="text-sm font-medium text-zinc-200">Statement of goal</span>
        <textarea name="goal_statement" required rows="3" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">{{ old('goal_statement', $entry->goal_statement ?? '') }}</textarea>
    </label>
    <div class="grid gap-5 md:grid-cols-2">
        <label class="block">
            <span class="text-sm font-medium text-zinc-200">GitHub repository</span>
            <input name="github_repository" type="url" value="{{ old('github_repository', $entry->github_repository ?? '') }}" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">
        </label>
        <label class="block">
            <span class="text-sm font-medium text-zinc-200">GitLab repository</span>
            <input name="gitlab_repository" type="url" value="{{ old('gitlab_repository', $entry->gitlab_repository ?? '') }}" class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">
        </label>
    </div>
    <label class="block">
        <span class="text-sm font-medium text-zinc-200">Assets</span>
        <input name="assets[]" type="file" multiple class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">
    </label>
</div>
<button class="mt-6 rounded-md bg-teal-300 px-5 py-3 font-semibold text-zinc-950 hover:bg-teal-200">{{ $button }}</button>
