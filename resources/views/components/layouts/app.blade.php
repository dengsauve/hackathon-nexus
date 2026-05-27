<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Hackathon Nexus') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-zinc-950 font-sans text-zinc-100 antialiased">
        <div class="min-h-screen bg-[radial-gradient(circle_at_top_left,rgba(20,184,166,0.16),transparent_28rem),radial-gradient(circle_at_top_right,rgba(244,63,94,0.12),transparent_24rem)]">
            <header class="border-b border-white/10">
                <nav class="mx-auto flex max-w-6xl items-center justify-between px-5 py-4">
                    <a href="{{ route('home') }}" class="text-sm font-semibold uppercase tracking-[0.18em] text-teal-200">
                        Hackathon Nexus
                    </a>

                    <div class="flex items-center gap-3 text-sm">
                        @auth
                            <a href="{{ route('portal') }}" class="rounded-md px-3 py-2 text-zinc-200 hover:bg-white/10">Portal</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="rounded-md bg-white px-3 py-2 font-medium text-zinc-950 hover:bg-teal-100">Log out</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="rounded-md px-3 py-2 text-zinc-200 hover:bg-white/10">Log in</a>
                            <a href="{{ route('register') }}" class="rounded-md bg-white px-3 py-2 font-medium text-zinc-950 hover:bg-teal-100">Create account</a>
                        @endauth
                    </div>
                </nav>
            </header>

            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
