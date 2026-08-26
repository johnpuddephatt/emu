<footer class="has-black-background-color">
    <div
        class="max-w-wide mx-auto grid items-start gap-10  px-4 md:px-6 lg:px-8 xl:px-12 py-12 lg:grid-cols-[auto_1fr_auto] lg:gap-16 lg:py-16">
        @svg('logo', 'w-24 h-auto flex-none fill-white')

        <div class="max-w-md">
            <p class="font-bold">
                {{ bloginfo('description') }}
            </p>
            <p class="mt-4 text-sm text-white/70">
                {{ __('East Marsh United is a registered charity (No. 1213614), incorporated on 4 November 2023.', 'sage') }}
            </p>
        </div>

        <div class="flex flex-wrap items-start gap-x-12 gap-y-8 lg:flex-nowrap lg:gap-16">
            @if (has_nav_menu('footer_navigation'))
                <nav aria-label="{{ wp_get_nav_menu_name('footer_navigation') }}">
                    {!! wp_nav_menu([
                        'theme_location' => 'footer_navigation',
                        'menu_class' => 'grid list-none grid-cols-2 gap-x-12 gap-y-2 p-0 text-sm [&_a]:no-underline [&_a:hover]:underline',
                        'container' => false,
                        'echo' => false,
                    ]) !!}
                </nav>
            @endif

            <div class="wp-block-button is-style-primary flex-none">
                <a class="wp-block-button__link wp-element-button" href="{{ home_url('/donate') }}">
                    {{ __('Donate', 'sage') }}
                </a>
            </div>
        </div>
    </div>
</footer>
