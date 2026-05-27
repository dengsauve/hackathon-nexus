<x-layouts.app title="Reset password - Hackathon Nexus">
    <x-auth-card title="Reset password" subtitle="Enter your account email and we will send a password reset link.">
        @if (session('status'))
            <div class="mb-5 rounded-md border border-teal-300/30 bg-teal-300/10 px-4 py-3 text-sm text-teal-100">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf
            <label class="block">
                <span class="text-sm font-medium text-zinc-200">Email</span>
                <input name="email" type="email" value="{{ old('email') }}" required autofocus class="mt-2 w-full rounded-md border border-white/10 bg-zinc-950 px-3 py-3 text-white outline-none ring-teal-300/50 focus:ring-2">
            </label>

            <button class="w-full rounded-md bg-teal-300 px-4 py-3 font-semibold text-zinc-950 hover:bg-teal-200">Send reset link</button>
        </form>

        <p class="mt-5 text-center text-sm text-zinc-400">
            Remembered it?
            <a href="{{ route('login') }}" class="font-semibold text-teal-200 hover:text-teal-100">Log in</a>
        </p>
    </x-auth-card>
</x-layouts.app>
