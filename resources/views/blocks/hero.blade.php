@unless ($block->preview)



    @if (!is_front_page())
        <a href="{{ home_url('/') }}" class="absolute top-4 left-4 z-30 lg:top-6 lg:left-6" aria-label="{{ $siteName }}">
            @svg('logo', 'h-auto w-24 fill-white lg:w-28')
        </a>
    @endif
    <div {{ $attributes }}>
    @endunless

    @if ($block->style === 'split')
        <section class="wp-block alignfull has-black-background-color animate-scroll-section relative overflow-x-clip">
            <div
                class="max-w-wide min-h-200  relative mx-auto grid items-center gap-12  px-4 md:px-6 lg:px-8 xl:px-12 pt-36 pb-24 lg:grid-cols-2 lg:gap-20 lg:py-32">
                <div class="prose prose-invert [&_.has-text-align-center]:text-left [&_.wp-block-buttons]:justify-start">
                    <InnerBlocks template="{{ $block->template }}" />
                </div>

                @if ($images)
                    {{--
                        Six slots scattered over the box, ordered so that any prefix
                        stays balanced — the gallery allows 4-6 photos, so slots 0-3
                        already cover top, middle and bottom, and 4-5 fill the gaps
                        rather than starting a new band. `max-h` matters as much as
                        `max-w`: portrait shots are otherwise tall enough to swallow two
                        bands on their own. Indexes 2 and 4 drop out below `lg`.

                        Positions are only half the story — the parallax swings each
                        photo through up to 40vh of travel, and neighbouring indexes
                        deliberately draw different `animate-scroll-*` keyframes so they
                        separate rather than drift as a block.
                    --}}
                    <div class="relative lg:h-160">
                        @foreach ($images as $image)
                            {!! wp_get_attachment_image($image, 'large', false, [
                                'sizes' => '(min-width: 1200px) 25vw, (min-width: 800px) 35vw, 50vw',
                                'class' =>
                                    '!my-0 absolute h-auto rounded-lg w-auto max-w-36 max-h-48 lg:max-w-52 lg:max-h-64 animate-scroll-' .
                                    (($loop->index % 3) + 1) .
                                    ' ' .
                                    match ($loop->index) {
                                        0 => 'top-0 -left-1/12 z-10 lg:max-w-60',
                                        1 => 'top-0 lg:top-7/12 right-0 z-10 lg:max-w-52',
                                        2 => 'hidden lg:block lg:top-[4%] lg:right-[2%] lg:max-w-44',
                                        3 => 'bottom-0 left-1/4 lg:top-[38%] lg:left-[10%] lg:max-w-48',
                                        4 => 'hidden lg:block lg:bottom-[6%] lg:left-0 lg:max-w-40',
                                        5 => 'bottom-0 left-7/12 lg:bottom-auto lg:top-[34%] lg:left-[42%] lg:max-w-40',
                                        default => 'hidden',
                                    },
                            ]) !!}
                        @endforeach
                    </div>
                @endif
            </div>

            @svg('streams-lines', 'pointer-events-none absolute top-0 right-0 w-1/4')
        </section>
    @else
        <section class="wp-block alignfull has-black-background-color animate-scroll-section relative overflow-x-clip">
            <div class="max-w-content relative mx-auto px-4 py-[20vh] text-center lg:py-48">
                @svg('logo', 'mx-auto h-auto mb-8 w-36 fill-white')

                <div class="[&_.wp-block-buttons]:justify-center prose prose-invert">
                    <InnerBlocks template="{{ $block->template }}" />
                </div>

                @if (shuffle($images))
                    @foreach ($images as $image)
                        {!! wp_get_attachment_image($image, 'large', false, [
                            'sizes' => '(min-width: 1200px) 30vw, (min-width: 800px) 40vw, 50vw',
                            'class' =>
                                '!my-0 absolute z-10 h-auto max-h-48 rounded-lg w-auto max-w-48 lg:max-w-72 lg:max-h-72 animate-scroll-' .
                                (($loop->index % 4) + 1) .
                                ' ' .
                                match ($loop->index) {
                                    0 => 'top-16 left-4 lg:top-1/5 lg:left-auto lg:right-full lg:translate-x-24 ',
                                    3 => 'hidden lg:block lg:top-10/12   lg:right-0  ',
                                    1 => 'hidden lg:block lg:top-11/12 lg:right-2/3  ',
                                    2 => 'bottom-0 left-8 lg:bottom-auto lg:top-1/2 lg:left-auto lg:right-full lg:-translate-x-24 ',
                                    5 => 'hidden lg:block lg:top-2/3 lg:left-full lg:translate-x-32 ',
                                    4 => 'top-4 right-4 lg:top-1/5 lg:right-auto lg:left-full lg:translate-x-10 ',
                                    default => 'hidden',
                                },
                        ]) !!}
                    @endforeach
                @endif
            </div>
        </section>
    @endif

    @unless ($block->preview)
    </div>
@endunless
