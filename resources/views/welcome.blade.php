<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name', 'Library') }} - Library Management System</title>

    <link rel="icon" href="/favicon.ico?v=2" sizes="any" />
    <link rel="icon" href="/favicon.svg?v=2" type="image/svg+xml" />
    <link rel="apple-touch-icon" href="/apple-touch-icon.png?v=2" />

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>
<body class="min-h-screen bg-zinc-50 font-sans text-zinc-900 antialiased dark:bg-zinc-950 dark:text-zinc-100">
    {{-- Header --}}
    <header class="sticky top-0 z-30 border-b border-zinc-200/70 bg-white/80 backdrop-blur supports-[backdrop-filter]:bg-white/70 dark:border-zinc-800 dark:bg-zinc-900/80 dark:supports-[backdrop-filter]:bg-zinc-900/70">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-3 px-4 py-3 sm:flex-nowrap sm:px-6 sm:py-0 sm:h-16">
            <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-2 sm:gap-3">
                <span class="flex size-8 sm:size-9 shrink-0 items-center justify-center rounded-lg bg-zinc-900 text-white dark:bg-white dark:text-zinc-900">
                    <x-app-logo-icon class="size-4 sm:size-5" />
                </span>
                <span class="truncate text-sm font-semibold tracking-tight sm:text-[15px]">{{ config('app.name', 'Library') }}</span>
                <span class="hidden rounded-full border border-zinc-200 bg-zinc-50 px-2.5 py-1 text-xs font-medium text-zinc-700 sm:inline-flex dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">Library System</span>
            </a>

            <div class="flex w-full items-center justify-between gap-2 sm:w-auto sm:justify-end">
                {{-- Appearance toggle — same logic as dashboard (resources/views/pages/settings/⚡appearance.blade.php:20, $flux.appearance) --}}
                <div x-data class="flex items-center rounded-full border border-zinc-200 bg-white p-1 dark:border-zinc-700 dark:bg-zinc-800">
                    <button type="button" @click="$flux.appearance = 'light'" :aria-pressed="($flux.appearance === 'light').toString()" :class="$flux.appearance === 'light' ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 shadow-sm' : 'text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100'" class="inline-flex size-7 items-center justify-center rounded-full text-xs transition sm:size-8" title="{{ __('Light') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25M4 12H1.75m3.386 6.364 1.591-1.591M12 18.75V21m4.95-4.05 1.591 1.591M7.05 7.05 5.459 5.459M12 12a2.25 2.25 0 0 0 0 0" /></svg>
                        <span class="sr-only">{{ __('Light') }}</span>
                    </button>
                    <button type="button" @click="$flux.appearance = 'dark'" :aria-pressed="($flux.appearance === 'dark').toString()" :class="$flux.appearance === 'dark' ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 shadow-sm' : 'text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100'" class="inline-flex size-7 items-center justify-center rounded-full text-xs transition sm:size-8" title="{{ __('Dark') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" /></svg>
                        <span class="sr-only">{{ __('Dark') }}</span>
                    </button>
                    <button type="button" @click="$flux.appearance = 'system'" :aria-pressed="($flux.appearance === 'system').toString()" :class="$flux.appearance === 'system' ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 shadow-sm' : 'text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100'" class="inline-flex size-7 items-center justify-center rounded-full text-xs transition sm:size-8" title="{{ __('System') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879.879l-4.242 4.242M9 17.25h6M9 17.25a3 3 0 0 1 3-3h0a3 3 0 0 1 3 3m-6 0V6.75A2.25 2.25 0 0 1 11.25 4.5h1.5A2.25 2.25 0 0 1 15 6.75v10.5" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6.75A2.25 2.25 0 0 0 18 4.5H6A2.25 2.25 0 0 0 3.75 6.75V18A2.25 2.25 0 0 0 6 20.25Z" /></svg>
                        <span class="sr-only">{{ __('System') }}</span>
                    </button>
                </div>

                @if (Route::has('login'))
                    <nav class="flex shrink-0 items-center gap-2">
                        @auth
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 sm:gap-2 rounded-full bg-zinc-900 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-800 sm:px-5 sm:text-sm dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100">
                                <span>Dashboard</span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-3.5 sm:size-4"><path fill-rule="evenodd" d="M5.22 14.78a.75.75 0 0 1 0-1.06L10.94 8 5.22 2.28a.75.75 0 1 1 1.06-1.06l6.25 6.25a.75.75 0 0 1 0 1.06L6.28 14.78a.75.75 0 0 1-1.06 0Z" clip-rule="evenodd" /></svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex rounded-full px-3 py-2 text-xs font-medium text-zinc-700 hover:text-zinc-900 sm:px-4 sm:text-sm dark:text-zinc-300 dark:hover:text-white">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center rounded-full bg-zinc-900 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-800 sm:px-5 sm:text-sm dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100">Get Started</a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>
        </div>
    </header>

    {{-- Hero --}}
    <main class="mx-auto max-w-6xl px-4 sm:px-6">
        <section class="grid items-center gap-8 py-8 sm:gap-10 sm:py-12 md:py-16 lg:grid-cols-2 lg:gap-12 lg:py-20">
            <div class="order-1 min-w-0">
                <div class="inline-flex max-w-full items-center gap-2 rounded-full border border-zinc-200 bg-white px-3 py-1 text-xs font-medium text-zinc-700 dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-300">
                    <span class="size-2 shrink-0 rounded-full bg-emerald-500"></span>
                    <span class="truncate">Open today · Borrow & return anytime</span>
                </div>

                <h1 class="mt-5 text-[30px] font-semibold tracking-tight leading-[1.1] sm:mt-6 sm:text-4xl md:text-5xl">
                    Your library,<br />
                    <span class="text-zinc-500 dark:text-zinc-400">simplified.</span>
                </h1>

                <p class="mt-4 max-w-xl text-[14px] leading-6 text-zinc-600 sm:text-[15px] dark:text-zinc-400">
                    Browse the catalog, borrow books in a click and keep track of due dates — all in one clean, simple place built for readers and librarians.
                </p>

                <div class="mt-6 flex flex-col gap-3 sm:mt-8 sm:flex-row sm:flex-wrap sm:items-center">
                    @auth
                        <a href="{{ route('books') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-zinc-900 px-6 py-3 text-sm font-medium text-white hover:bg-zinc-800 sm:w-auto dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292M12 6.042A8.967 8.967 0 0 1 18 3.75c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292M12 6.042V18.75" /></svg>
                            Browse Catalog
                        </a>
                        <a href="{{ route('my-borrowed-book') }}" class="inline-flex w-full items-center justify-center rounded-full border border-zinc-200 bg-white px-6 py-3 text-sm font-medium text-zinc-800 hover:bg-zinc-50 sm:w-auto dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-800">
                            My Borrowed Books
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-zinc-900 px-6 py-3 text-sm font-medium text-white hover:bg-zinc-800 sm:w-auto dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292M12 6.042A8.967 8.967 0 0 1 18 3.75c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292M12 6.042V18.75" /></svg>
                            Browse Catalog
                        </a>
                        <a href="{{ Route::has('register') ? route('register') : route('login') }}" class="inline-flex w-full items-center justify-center rounded-full border border-zinc-200 bg-white px-6 py-3 text-sm font-medium text-zinc-800 hover:bg-zinc-50 sm:w-auto dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-800">
                            Create account
                        </a>
                    @endauth
                </div>

                <div class="mt-6 flex flex-wrap gap-x-6 gap-y-2 text-xs leading-5 text-zinc-600 sm:mt-8 dark:text-zinc-400">
                    <span class="inline-flex items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4 shrink-0 text-emerald-500"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" /></svg> Fast search by title, author, ISBN</span>
                    <span class="inline-flex items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4 shrink-0 text-emerald-500"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" /></svg> Real-time availability</span>
                </div>
            </div>

            {{-- Visual card --}}
            <div class="order-2 relative min-w-0">
                <div class="rounded-2xl border border-zinc-200 bg-white p-4 shadow-sm sm:rounded-[28px] sm:p-6 dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Featured Collection</p>
                        <span class="shrink-0 rounded-full bg-zinc-900 px-3 py-1 text-xs font-medium text-white dark:bg-white dark:text-zinc-900">New arrivals</span>
                    </div>

                    {{-- Fake search --}}
                    <div class="mt-5 flex items-center gap-2 rounded-full border border-zinc-200 bg-zinc-50 px-4 py-2.5 text-sm text-zinc-600 sm:mt-6 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 shrink-0 text-zinc-500 dark:text-zinc-400"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                        <span class="truncate">Search books, authors, ISBN...</span>
                    </div>

                    {{-- Book rows --}}
                    <div class="mt-5 space-y-3 sm:mt-6">
                        <div class="flex gap-3 rounded-2xl border border-zinc-200 p-3 sm:gap-4 dark:border-zinc-700">
                            <div class="flex h-16 w-12 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-amber-200 to-orange-300 text-xs font-bold text-zinc-800">FIC</div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-zinc-900 dark:text-zinc-100">The Great Library</p>
                                <p class="truncate text-xs text-zinc-600 dark:text-zinc-400">A. Bennett · ISBN 978-0-00-000000-1</p>
                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                    <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">3 copies available</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-3 rounded-2xl border border-zinc-200 p-3 sm:gap-4 dark:border-zinc-700">
                            <div class="flex h-16 w-12 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-sky-200 to-indigo-300 text-xs font-bold text-zinc-800">SCI</div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-zinc-900 dark:text-zinc-100">Introduction to Systems</p>
                                <p class="truncate text-xs text-zinc-600 dark:text-zinc-400">K. Rivera · ISBN 978-0-00-000000-2</p>
                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                    <span class="rounded-full bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-700 dark:bg-amber-950/60 dark:text-amber-300">1 copy left</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-3 rounded-2xl border border-dashed border-zinc-200 p-3 opacity-90 sm:gap-4 dark:border-zinc-700">
                            <div class="flex h-16 w-12 shrink-0 items-center justify-center rounded-lg bg-zinc-100 text-xs font-bold text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400">HIS</div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-zinc-900 dark:text-zinc-100">Borrowed — Due in 3 days</p>
                                <p class="truncate text-xs text-zinc-600 dark:text-zinc-400">Return to avoid late fee</p>
                            </div>
                            <span class="self-center shrink-0 rounded-full bg-zinc-900 px-3 py-1.5 text-xs font-medium text-white dark:bg-white dark:text-zinc-900">Return</span>
                        </div>
                    </div>

                    <p class="mt-6 text-center text-xs font-medium text-zinc-500 dark:text-zinc-400">Simple • Minimal • Fast</p>
                </div>

                {{-- Decorative blur — hidden on very small to avoid overflow --}}
                <div class="pointer-events-none absolute -right-4 -top-4 -z-10 hidden h-32 w-32 rounded-full bg-amber-200/40 blur-3xl sm:block dark:bg-amber-500/10"></div>
                <div class="pointer-events-none absolute -bottom-4 -left-4 -z-10 hidden h-32 w-32 rounded-full bg-sky-200/40 blur-3xl sm:block dark:bg-sky-500/10"></div>
            </div>
        </section>

        {{-- Features --}}
        <section class="grid grid-cols-1 gap-4 border-t border-zinc-200 py-8 sm:py-10 md:grid-cols-3 dark:border-zinc-800">
            <div class="rounded-2xl border border-zinc-200 bg-white p-5 sm:p-6 dark:border-zinc-800 dark:bg-zinc-900">
                <div class="flex size-9 items-center justify-center rounded-full bg-zinc-900 text-white dark:bg-white dark:text-zinc-900">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292M12 6.042A8.967 8.967 0 0 1 18 3.75c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292M12 6.042V18.75" /></svg>
                </div>
                <h3 class="mt-4 text-sm font-semibold text-zinc-900 dark:text-zinc-100">Catalog & Inventory</h3>
                <p class="mt-1 text-sm leading-6 text-zinc-600 dark:text-zinc-400">Search by title, author or ISBN. See live stock — total and available copies at a glance.</p>
            </div>
            <div class="rounded-2xl border border-zinc-200 bg-white p-5 sm:p-6 dark:border-zinc-800 dark:bg-zinc-900">
                <div class="flex size-9 items-center justify-center rounded-full bg-zinc-900 text-white dark:bg-white dark:text-zinc-900">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                </div>
                <h3 class="mt-4 text-sm font-semibold text-zinc-900 dark:text-zinc-100">Borrow in One Click</h3>
                <p class="mt-1 text-sm leading-6 text-zinc-600 dark:text-zinc-400">Members can borrow instantly. Availability updates automatically — no double bookings.</p>
            </div>
            <div class="rounded-2xl border border-zinc-200 bg-white p-5 sm:p-6 md:col-span-1 sm:col-span-2 lg:col-span-1 dark:border-zinc-800 dark:bg-zinc-900">
                <div class="flex size-9 items-center justify-center rounded-full bg-zinc-900 text-white dark:bg-white dark:text-zinc-900">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                </div>
                <h3 class="mt-4 text-sm font-semibold text-zinc-900 dark:text-zinc-100">Track & Return</h3>
                <p class="mt-1 text-sm leading-6 text-zinc-600 dark:text-zinc-400">View borrowed books, due dates and history from your dashboard. Returning restores stock.</p>
            </div>
        </section>

        {{-- CTA --}}
        <section class="pb-8 sm:pb-6">
            <div class="flex flex-col gap-4 rounded-2xl border border-zinc-200 bg-white px-5 py-6 sm:flex-row sm:items-center sm:justify-between sm:px-6 dark:border-zinc-800 dark:bg-zinc-900">
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Ready to start reading?</p>
                    <p class="mt-1 text-sm leading-5 text-zinc-600 dark:text-zinc-400">Log in to borrow books or create an account in seconds.</p>
                </div>
                <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row">
                    <a href="{{ route('login') }}" class="inline-flex w-full items-center justify-center rounded-full bg-zinc-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-zinc-800 sm:w-auto dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-100">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-flex w-full items-center justify-center rounded-full border border-zinc-200 bg-white px-5 py-2.5 text-sm font-medium text-zinc-800 hover:bg-zinc-50 sm:w-auto dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-800">Register</a>
                    @endif
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-zinc-200 py-6 dark:border-zinc-800">
        <div class="mx-auto flex max-w-6xl flex-col items-center gap-2 px-4 text-center text-xs leading-5 text-zinc-600 sm:flex-row sm:justify-between sm:px-6 sm:text-left dark:text-zinc-400">
            <p>© {{ date('Y') }} {{ config('app.name', 'Library') }}. Library Management System.</p>
            <p class="flex items-center gap-1.5">Built with <span class="text-red-500">♥</span> using Laravel & Flux</p>
        </div>
    </footer>

    @fluxScripts
</body>
</html>
