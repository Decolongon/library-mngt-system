<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" {{ $attributes }}>
    {{-- Library mark: open book with bookmark — uses currentColor so it inherits contrast from whatever wrapper it is placed in (e.g. bg-[var(--color-accent)] text-[var(--color-accent-foreground)] on the welcome page) --}}
    {{-- Book outline --}}
    <path
        fill="none"
        stroke="currentColor"
        stroke-width="2.1"
        stroke-linecap="round"
        stroke-linejoin="round"
        d="M5.8 8.2c1.5-.55 3.2-.85 5-.85 2.6 0 4.7 1 5.2 1.35.5-.35 2.6-1.35 5.2-1.35 1.8 0 3.5.3 5 .85v13.9c-1.5-.55-3.2-.85-5-.85-1.9 0-3.5.5-5.2 1.35-1.7-.85-3.3-1.35-5.2-1.35-1.8 0-3.5.3-5 .85z"
    />
    {{-- Spine --}}
    <path fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" d="M16 8.7v13.9" />
    {{-- Pages detail lines --}}
    <path fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" opacity="0.55" d="M9.5 11.2h3.2M9.5 13.7h3.2M19.3 11.2h3.2M19.3 13.7h3.2" />
    {{-- Bookmark ribbon on right page — solid currentColor for contrast --}}
    <path fill="currentColor" d="M19.8 8.8h3.6v6.2l-1.8-1.5-1.8 1.5z" />
</svg>
