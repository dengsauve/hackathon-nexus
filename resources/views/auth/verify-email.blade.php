<x-layouts.app title="Verify email - Hackathon Nexus">
    <x-auth-card title="Verify your email" subtitle="Confirm your email address before opening the portal.">
        @if (session('status') === 'verification-link-sent')
            <div class="mb-5 rounded-md border border-teal-300/30 bg-teal-300/10 px-4 py-3 text-sm text-teal-100">
                A fresh verification link has been sent.
            </div>
        @endif

        <p class="text-sm leading-6 text-zinc-300">
            We sent a verification link to <span class="font-semibold text-white">{{ auth()->user()->email }}</span>.
        </p>

        <div class="mt-6 grid gap-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button class="w-full rounded-md bg-teal-300 px-4 py-3 font-semibold text-zinc-950 hover:bg-teal-200">Resend verification email</button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full rounded-md border border-white/15 px-4 py-3 text-sm font-semibold text-white hover:bg-white/10">Log out</button>
            </form>
        </div>
    </x-auth-card>
</x-layouts.app>
