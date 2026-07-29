@unless ($block->preview)
    <div {{ $attributes }}>
    @endunless

    <section class="wp-block relative py-12 lg:py-20">
        @svg('green-line', 'pointer-events-none absolute h-auto left-0 top-0 -translate-y-2/5 rotate-y-180 w-screen ')
        <div class="max-w-wide relative mx-auto px-4 lg:px-16">
            @svg('loop', 'pointer-events-none absolute -top-6 right-4 hidden h-auto w-18 rotate-6 lg:block xl:-right-10')

            <div class="max-w-content prose relative">
                <InnerBlocks template="{{ $block->template }}" />
            </div>

            @if ($featured)
                <article class="group relative mt-10 grid items-center gap-8 lg:grid-cols-2">
                    <div class="overflow-hidden">
                        @if (has_post_thumbnail($featured))
                            {!! get_the_post_thumbnail($featured, 'large', [
                                'class' => 'aspect-[4/3] w-full object-cover group-hover:scale-105 duration-500 transition',
                                'sizes' => '(min-width: 1024px) 45vw, 90vw',
                            ]) !!}
                        @else
                            <div class="bg-gray-light aspect-[4/3] w-full"></div>
                        @endif
                    </div>

                    <div>
                        <h3 class="type-lg">{!! get_the_title($featured) !!}</h3>

                        <p class="mt-4">{!! get_the_excerpt($featured) !!}</p>

                        <a href="{{ get_permalink($featured) }}"
                            class="wp-element-button mt-6 !px-4 !py-1.5 !text-xs before:absolute before:inset-0">
                            {{ __('Read more', 'sage') }}
                        </a>
                    </div>
                </article>
            @endif

            @if ($posts)
                <div class="mt-16 grid gap-8 sm:grid-cols-2 lg:mt-24 lg:grid-cols-3 lg:gap-16">
                    @foreach ($posts as $post)
                        <article class="group relative">
                            <div class="overflow-hidden">
                                @if (has_post_thumbnail($post))
                                    {!! get_the_post_thumbnail($post, 'medium_large', [
                                        'class' => 'aspect-[4/3] w-full object-cover group-hover:scale-105 duration-1000 transition',
                                        'sizes' => '(min-width: 1024px) 30vw, 90vw',
                                    ]) !!}
                                @else
                                    <div class="bg-gray-light aspect-[4/3] w-full"></div>
                                @endif
                            </div>

                            <h4 class="type-md mt-4 min-h-16">{!! get_the_title($post) !!}</h4>

                            <p class="mt-2 text-sm">{!! get_the_excerpt($post) !!}</p>

                            <a href="{{ get_permalink($post) }}"
                                class="wp-element-button mt-4 !px-4 !py-1.5 !text-xs before:absolute before:inset-0">
                                {{ __('Read more', 'sage') }}
                            </a>
                        </article>
                    @endforeach
                </div>
            @endif

            @unless ($featured)
                <p class="text-gray mt-8">{{ __('No posts published yet — the latest news will appear here.', 'sage') }}</p>
            @endunless
        </div>
    </section>

    @unless ($block->preview)
    </div>
@endunless
