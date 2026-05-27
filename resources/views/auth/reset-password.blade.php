<x-layouts.app title="Choose a new password - Hackathon Nexus">
    <x-auth-card title="Choose a new password" subtitle="Create a fresh password for your account.">
        <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <label class="block">
                <span class="text-sm font-medium text-zinc-200">Email</span>
                <input name="email" type="email" value="{{ old('email', $request->email) }}" required autofocus class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">
            </label>

            <label class="block">
                <span class="text-sm font-medium text-zinc-200">New password</span>
                <div class="mt-2 flex rounded-md border border-white/10 bg-zinc-950 focus-within:ring-2 focus-within:ring-teal-300/50">
                    <input id="password" name="password" type="password" required class="min-w-0 flex-1 bg-transparent px-3 py-3 text-white outline-none">
                    <button type="button" class="px-3 text-sm font-medium text-teal-200" data-password-toggle="#password" aria-pressed="false">Show</button>
                </div>
            </label>

            <label class="block">
                <span class="text-sm font-medium text-zinc-200">Confirm password</span>
                <input name="password_confirmation" type="password" required class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">
            </label>

            <button class="w-full rounded-md bg-teal-300 px-4 py-3 font-semibold text-zinc-950 hover:bg-teal-200">Update password</button>
        </form>
    </x-auth-card>
</x-layouts.app>
