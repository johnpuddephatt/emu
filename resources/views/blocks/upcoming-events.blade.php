@unless ($block->preview)
    <div {{ $attributes }}>
@endunless

<section class="wp-block alignfull bg-cream relative overflow-x-clip">
    <div class="max-w-wide mx-auto grid items-center gap-10 px-4 lg:grid-cols-2">
        <div class="hidden self-end lg:block">
            @svg('lines_alt', 'absolute left-0 bottom-0 h-[calc(100%+6rem)] w-auto')
            @if ($illustration)
                {!!
                    wp_get_attachment_image($illustration, 'large', false, [
                        'class' => 'mx-auto relative max-h-[40rem] w-auto',
                        'sizes' => '(min-width: 1024px) 40vw, 0px',
                    ])
                !!}
            @endif
        </div>

        <div class="py-12">
            <div class="prose">
                <InnerBlocks template="{{ $block->template }}" />
            </div>

            @if ($events)
                <ul class="mt-8 list-none space-y-8 p-0">
                    @foreach ($events as $event)
                        <li>
                            <h3 class="type-md">
                                <a href="{{ get_permalink($event) }}" class="no-underline hover:underline">
                                    {{ get_the_title($event) }}
                                </a>
                            </h3>

                            @if ($start = get_field('start', $event->ID))
                                <time
                                    datetime="{{ date('c', strtotime($start)) }}"
                                    class="mt-1 block text-sm font-bold"
                                >{{ wp_date('D jS M', strtotime($start)) }}</time>
                            @endif

                            @if (has_excerpt($event))
                                <p class="mt-1 text-sm">{{ get_the_excerpt($event) }}</p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="mt-8">{{ __('No upcoming events — check back soon!', 'sage') }}</p>
            @endif

            @if ($all_events_link)
                <a
                    href="{{ $all_events_link['url'] }}"
                    class="wp-element-button mt-10"
                >{{ $all_events_link['title'] ?: __('See all events', 'sage') }}</a>
            @endif
        </div>
    </div>
</section>

@unless ($block->preview)
    </div>
@endunless
