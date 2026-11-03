@unless ($block->preview)



    @if (!is_front_page())
        <a href="{{ home_url('/') }}" class="absolute top-4 left-4 z-30 lg:top-6 lg:left-6" aria-label="{{ $siteName }}">
            @svg('logo', 'h-auto w-24 fill-white lg:w-28')
        </a>
    @endif
    <div {{ $attributes }}>
    @endunless

    @if ($block->style === 'split')
        <section
            class="wp-block alignfull is-bleed has-black-background-color animate-scroll-section relative overflow-x-clip">
            <div
                class="max-w-wide min-h-200  relative mx-auto grid items-center gap-12  px-4 md:px-6 lg:px-8 xl:px-12 pt-36 pb-24 lg:grid-cols-2 lg:gap-20 lg:py-32">
                <div class="prose prose-invert [&_.has-text-align-center]:text-left [&_.wp-block-buttons]:justify-start">
                    <InnerBlocks template="{{ $block->template }}" />
                </div>

                @if ($images)
                    {{--
                        Three columns of two, staggered. Every photo's long edge is
                        capped at the same size (30% of the box, via `cqw`), so
                        portrait and landscape shots carry similar weight and nothing
                        is wider than its column, so the columns never collide.

                        Each column has a single `animate-scroll-*` speed: photos
                        in the same column move together, and the parallax comes
                        from the columns moving against each other. Slots are
                        ordered so any 4 of the 6 still spread across the box.
                    --}}
                    <div class="relative h-96 [container-type:inline-size] sm:h-112 lg:h-144">
                        @foreach ($images as $image)
                            {!! wp_get_attachment_image($image, 'large', false, [
                                'sizes' => '(min-width: 1024px) 15vw, 30vw',
                                'class' =>
                                    '!my-0 absolute z-10 h-auto w-auto max-w-[30cqw] max-h-[30cqw] -translate-x-1/2 rounded-lg ' .
                                    match ($loop->index) {
                                        0 => 'left-[14%] top-0 animate-scroll-1',
                                        1 => 'left-[84%] top-[56%] animate-scroll-3',
                                        2 => 'left-[51%] top-[16%] animate-scroll-2',
                                        3 => 'left-[16.5%] top-[48%] animate-scroll-1',
                                        4 => 'left-[86%] top-[6%] animate-scroll-3',
                                        5 => 'left-[49%] top-[64%] animate-scroll-2',
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
        {{--
            Photos are positioned against the section, not the text column.
            Below lg they sit above and below the text (and either side of the
            logo); from lg they move into the two side gutters, centred in
            the space either side of the 48rem content column and sized to
            fit it (`--hero-photo` is each photo's long edge).

            Parallax is already part-applied at scroll 0, so the top photos use
            the gentle `animate-scroll-3` (it barely shifts them on load, which
            keeps them clear of the menu button), and nothing lower in a column
            rises faster than the photo above it.
        --}}
        <section
            class="wp-block alignfull is-bleed has-black-background-color animate-scroll-section relative overflow-x-clip [--hero-gutter:calc(50%-24rem)] [--hero-photo:6rem] md:[--hero-photo:7rem] lg:[--hero-photo:clamp(6rem,calc((50vw-24rem)*0.75),14rem)]">
            <div class="max-w-content mx-auto px-4 pb-[30vh] pt-[20vh] text-center lg:py-48">
                @svg('logo', 'mx-auto h-auto mb-8 w-36 fill-white')

                <div class="[&_.wp-block-buttons]:justify-center prose prose-invert">
                    <InnerBlocks template="{{ $block->template }}" />
                </div>
            </div>

            @if (shuffle($images))
                @foreach ($images as $image)
                    {!! wp_get_attachment_image($image, 'large', false, [
                        'sizes' => '(min-width: 1024px) 14rem, 7rem',
                        'class' =>
                            '!my-0 absolute z-10 h-auto w-auto max-w-(--hero-photo) max-h-(--hero-photo) rounded-lg ' .
                            match ($loop->index) {
                                // left gutter, top to bottom
                                0 => 'left-4 top-6 animate-scroll-3 lg:top-[9%] lg:left-[calc(var(--hero-gutter)*0.42)] lg:-translate-x-1/2',
                                2 => 'left-4 top-[calc(20vh+3.5rem)] animate-scroll-1 lg:top-[38%] lg:left-[calc(var(--hero-gutter)*0.58)] lg:-translate-x-1/2',
                                1 => 'bottom-[6%] left-4 animate-scroll-1 lg:bottom-[5%] lg:left-[calc(var(--hero-gutter)*0.5)] lg:-translate-x-1/2',
                                // right gutter, top to bottom
                                4 => 'right-4 top-24 animate-scroll-3 lg:top-[14%] lg:right-[calc(var(--hero-gutter)*0.5)] lg:translate-x-1/2',
                                5 => 'bottom-[4%] right-1/2 translate-x-1/2 animate-scroll-3 lg:bottom-auto lg:top-[44%] lg:right-[calc(var(--hero-gutter)*0.4)]',
                                3 => 'bottom-[8%] right-4 animate-scroll-1 lg:bottom-[2%] lg:right-[calc(var(--hero-gutter)*0.58)] lg:translate-x-1/2',
                                default => 'hidden',
                            },
                    ]) !!}
                @endforeach
            @endif
        </section>
    @endif

    @unless ($block->preview)
    </div>
@endunless
