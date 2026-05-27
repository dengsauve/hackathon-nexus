<x-layouts.app title="Log in - Hackathon Nexus">
    <x-auth-card title="Log in" subtitle="Use email and password, or continue with a connected OAuth provider.">
        <div class="grid gap-3">
            <a href="{{ route('auth.social.redirect', 'google') }}" class="rounded-md border border-white/15 px-4 py-3 text-center text-sm font-semibold text-white hover:bg-white/10">Continue with Google</a>
            <a href="{{ route('auth.social.redirect', 'github') }}" class="rounded-md border border-white/15 px-4 py-3 text-center text-sm font-semibold text-white hover:bg-white/10">Continue with GitHub</a>
        </div>

        <div class="my-6 flex items-center gap-3 text-xs uppercase tracking-wide text-zinc-500">
            <span class="h-px flex-1 bg-white/10"></span>
            Email login
            <span class="h-px flex-1 bg-white/10"></span>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <label class="block">
                <span class="text-sm font-medium text-zinc-200">Email</span>
                <input name="email" type="email" value="{{ old('email') }}" required autofocus class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">
            </label>

            <label class="block">
                <span class="text-sm font-medium text-zinc-200">Password</span>
                <div class="mt-2 flex rounded-md border border-white/10 bg-zinc-950 focus-within:ring-2 focus-within:ring-teal-300/50">
                    <input id="password" name="password" type="password" required class="min-w-0 flex-1 bg-transparent px-3 py-3 text-white outline-none">
                    <button type="button" class="px-3 text-sm font-medium text-teal-200" data-password-toggle="#password" aria-pressed="false">Show</button>
                </div>
            </label>

            <label class="flex items-center gap-2 text-sm text-zinc-300">
                <input type="checkbox" name="remember" class="rounded border-white/20 bg-zinc-950 text-teal-300">
                Remember me
            </label>

            <button class="w-full rounded-md bg-teal-300 px-4 py-3 font-semibold text-zinc-950 hover:bg-teal-200">Log in</button>
        </form>

        <p class="mt-5 text-center text-sm text-zinc-400">
            Need an account?
            <a href="{{ route('register') }}" class="font-semibold text-teal-200 hover:text-teal-100">Create one</a>
        </p>
    </x-auth-card>
</x-layouts.app>
