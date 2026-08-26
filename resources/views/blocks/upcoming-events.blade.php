@php
    // The editor iframe scrolls independently, so the scroll-driven reveal
    // would leave the illustration parked off-screen — render it static there.
    $animate = $illustration && $animate_illustration && !$block->preview;
@endphp

@unless ($block->preview)
    <div {{ $attributes }}>
    @endunless

    <section class="wp-block alignfull is-bleed bg-cream relative overflow-x-clip">
        <div class="max-w-wide mx-auto grid items-center gap-10  px-4 md:px-6 lg:px-8 xl:px-12 lg:grid-cols-2">
            <div class="hidden self-end lg:block {{ $animate ? 'animate-reveal-scope' : '' }}">
                @svg('lines_alt', 'absolute left-0 bottom-0 h-[calc(100%+6rem)] w-auto')
                @if ($illustration)
                    @php
                        $image = wp_get_attachment_image($illustration, 'large', false, [
                            'class' => 'mx-auto relative max-h-[40rem] w-auto' . ($animate ? ' animate-reveal-up' : ''),
                            'sizes' => '(min-width: 1024px) 40vw, 0px',
                        ]);
                    @endphp

                    {{-- When animating, a mask hugging the illustration keeps it out
                     of sight at translateY(100%). --}}
                    @if ($animate)
                        <div class="overflow-hidden">{!! $image !!}</div>
                    @else
                        {!! $image !!}
                    @endif
                @endif
            </div>

            <div class="py-12">
                <div class="prose">
                    <InnerBlocks template="{{ $block->template }}" />
                </div>

                @if ($events)
                    <ul class="mt-8 list-none space-y-8 p-0">
                        @foreach ($events as $event)
                            <li class=" relative p-6 bg-white rounded-xl group">
                                <div
                                    class="pointer-events-none border-2  border-yellow group-hover:rotate-0 transition  rounded-xl absolute inset-0 {{ $loop->odd ? '-rotate-1' : 'rotate-1' }} ">
                                </div>
                                @if ($start = get_field('start', $event->ID))
                                    <time datetime="{{ date('c', strtotime($start)) }}"
                                        class="mb-2 block text-sm font-bold">{{ wp_date('D jS M', strtotime($start)) }}</time>
                                @endif
                                <h3 class="type-md mb-2">
                                    <a href="{{ get_permalink($event) }}"
                                        class="before:inset-0 before:absolute no-underline hover:underline">
                                        {{ get_the_title($event) }}
                                    </a>
                                </h3>



                                @if (has_excerpt($event))
                                    <p class="mt-0 text-sm">{{ get_the_excerpt($event) }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="mt-8">{{ __('No upcoming events — check back soon!', 'sage') }}</p>
                @endif

                @if ($all_events_link)
                    <a href="{{ $all_events_link['url'] }}"
                        class="wp-element-button mt-10">{{ $all_events_link['title'] ?: __('See all events', 'sage') }}</a>
                @endif
            </div>
        </div>
    </section>

    @unless ($block->preview)
    </div>
@endunless
