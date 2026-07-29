<header class="absolute top-0 right-0 z-40 p-4 lg:p-6" x-data="{ open: false }">
    <button
        type="button"
        class="flex items-center gap-2 font-bold text-white mix-blend-difference"
        @click="open = true"
        aria-label="{{ __('Open menu', 'sage') }}"
    >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" class="size-5">
            <path d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
        </svg>
        {{ __('Menu', 'sage') }}
    </button>

    <div
        x-cloak
        x-show="open"
        x-transition.opacity.duration.300ms
        @keydown.escape.window="open = false"
        class="has-black-background-color fixed inset-0 z-50 flex flex-col"
    >
        <div class="flex justify-end p-4 lg:p-6">
            <button
                type="button"
                class="flex items-center gap-2 font-bold"
                @click="open = false"
                aria-label="{{ __('Close menu', 'sage') }}"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" class="size-5">
                    <path d="M6 6l12 12M18 6L6 18" />
                </svg>
                {{ __('Close', 'sage') }}
            </button>
        </div>

        @if (has_nav_menu('primary_navigation'))
            <nav
                class="flex flex-1 items-center justify-center overflow-y-auto"
                aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}"
            >
                {!!
                    wp_nav_menu([
                        'theme_location' => 'primary_navigation',
                        'menu_class' => 'menu-stagger flex list-none flex-col items-center gap-6 p-0 text-center text-3xl font-bold [&_a]:no-underline [&_a:hover]:underline',
                        'container' => false,
                        'echo' => false,
                    ])
                !!}
            </nav>
        @endif

        @svg('logo-shape', 'pointer-events-none mx-auto mb-8 h-auto w-20 shrink-0 text-white')
    </div>
</header>
