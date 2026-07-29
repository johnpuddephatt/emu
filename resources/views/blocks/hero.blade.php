@unless ($block->preview)
    <div {{ $attributes }}>
    @endunless

    @if ($block->style === 'split')
        <section class="wp-block alignfull has-black-background-color animate-scroll-section relative overflow-x-clip">
            <div
                class="max-w-wide relative mx-auto grid items-center gap-12 px-4 pt-16 pb-24 lg:grid-cols-2 lg:gap-20 lg:py-32">
                <div class="prose prose-invert [&_.has-text-align-center]:text-left [&_.wp-block-buttons]:justify-start">
                    <InnerBlocks template="{{ $block->template }}" />
                </div>

                @if ($images)
                    <div class="relative min-h-96 lg:min-h-[34rem]">
                        @foreach ($images as $image)
                            {!! wp_get_attachment_image($image, 'large', false, [
                                'sizes' => '(min-width: 1200px) 25vw, (min-width: 800px) 35vw, 50vw',
                                'class' =>
                                    '!my-0 absolute h-auto rounded-lg w-auto max-w-36 lg:max-w-64 animate-scroll-' .
                                    (($loop->index % 3) + 1) .
                                    ' ' .
                                    match ($loop->index) {
                                        0 => 'bottom-0 left-1/4 z-10 translate-y-1/4 lg:max-w-72',
                                        1 => 'top-0 left-0 z-10 lg:max-w-72',
                                        2 => 'hidden lg:block lg:top-2/3 lg:right-4 lg:max-w-40',
                                        1 => 'top-16 left-1/2 -translate-x-1/3 max-w-28 lg:max-w-44',
                                        4 => 'hidden lg:block lg:top-1/2 lg:left-0 lg:max-w-36',
                                        5 => 'top-32 right-0 lg:top-40 lg:max-w-56',
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
                                '!my-0 absolute z-10 h-auto rounded-lg w-auto max-w-36 lg:max-w-72 lg:max-h-72 animate-scroll-' .
                                (($loop->index % 4) + 1) .
                                ' ' .
                                match ($loop->index) {
                                    0 => '-top-4 left-4 lg:top-1/5 lg:left-auto lg:right-full lg:translate-x-24 ',
                                    3 => 'hidden lg:block lg:top-11/12   lg:right-0  ',
                                    1 => 'hidden lg:block lg:top-5/6 lg:right-2/3  ',
                                    2 => 'bottom-0 left-8 lg:bottom-auto lg:top-1/2 lg:left-auto lg:right-full lg:-translate-x-24 ',
                                    5 => 'hidden lg:block lg:top-2/3 lg:left-full lg:translate-x-32 ',
                                    4 => '-top-4 right-4 lg:top-1/5 lg:right-auto lg:left-full lg:translate-x-10 ',
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
