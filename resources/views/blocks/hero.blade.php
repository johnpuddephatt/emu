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
                        A loose collage on a square box, the same at every width.
                        Each slot is a square of the given size (long edge, in
                        `cqw` of the box), and the photo is centred in it, so a
                        portrait or landscape shot never spills out of its slot. Two
                        large, three medium and one small, laid out so no two slots
                        overlap.

                        Parallax: where two slots share any horizontal space, the
                        upper one never moves slower than the lower one, so they
                        drift apart as you scroll. Slots are ordered so that any 4
                        of the 6 still spread across the box (big top-left, big
                        bottom-right, then the gaps).
                    --}}
                    <div class="relative aspect-square w-full max-w-xl [container-type:inline-size] lg:max-w-none">
                        @foreach ($images as $image)
                            {!! wp_get_attachment_image($image, 'large', false, [
                                'sizes' => '(min-width: 1024px) 20vw, 40vw',
                                'class' =>
                                    '!my-0 absolute z-10 h-auto w-auto -translate-1/2 rounded-lg ' .
                                    match ($loop->index) {
                                        0 => 'left-[19%] top-[23%] max-w-[38cqw] max-h-[38cqw] animate-scroll-3',
                                        1 => 'left-[82%] top-[78%] max-w-[36cqw] max-h-[36cqw] animate-scroll-2',
                                        2 => 'left-[56%] top-[12%] max-w-[24cqw] max-h-[24cqw] animate-scroll-2',
                                        3 => 'left-[28%] top-[79%] max-w-[26cqw] max-h-[26cqw] animate-scroll-2',
                                        4 => 'left-[85%] top-[29%] max-w-[30cqw] max-h-[30cqw] animate-scroll-3',
                                        5 => 'left-[56%] top-[48%] max-w-[28cqw] max-h-[28cqw] animate-scroll-3',
                                        default => 'hidden',
                                    },
                            ]) !!}
                        @endforeach
                    </div>
                @endif
            </div>

            @svg('streams-lines', 'pointer-events-none absolute top-0 right-0 w-1/2 lg:w-1/4')
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
                                0
                                    => 'left-4 top-6 animate-scroll-3 lg:top-[9%] lg:left-[calc(var(--hero-gutter)*0.42)] lg:-translate-x-1/2',
                                2
                                    => 'left-4 top-[calc(20vh+3.5rem)] animate-scroll-1 lg:top-[38%] lg:left-[calc(var(--hero-gutter)*0.58)] lg:-translate-x-1/2',
                                1
                                    => 'bottom-[6%] left-4 animate-scroll-1 lg:bottom-[5%] lg:left-[calc(var(--hero-gutter)*0.5)] lg:-translate-x-1/2',
                                // right gutter, top to bottom
                                4
                                    => 'right-4 top-24 animate-scroll-3 lg:top-[14%] lg:right-[calc(var(--hero-gutter)*0.5)] lg:translate-x-1/2',
                                5
                                    => 'bottom-[4%] right-1/2 translate-x-1/2 animate-scroll-3 lg:bottom-auto lg:top-[44%] lg:right-[calc(var(--hero-gutter)*0.4)]',
                                3
                                    => 'bottom-[8%] right-4 animate-scroll-1 lg:bottom-[2%] lg:right-[calc(var(--hero-gutter)*0.58)] lg:translate-x-1/2',
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
