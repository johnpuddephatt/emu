@php
    $light = $block->style === 'light' || $block->style === 'light_alt';

    // The editor iframe scrolls independently, so the scroll-driven reveal
    // would leave the image parked off-screen — render it static there.
    $animate = $image && $animate_image && !$block->preview;
@endphp

@unless ($block->preview)
    <div {{ $attributes }}>
    @endunless

    <section
        class="wp-block [&_.wp-element-button::before]:content-[''] [&_.wp-element-button::before]:inset-0 [&_.wp-element-button::before]:absolute group relative my-12 {{ $block->block->align === 'full' ? 'alignfull px-4' : 'alignwide' }} {{ $animate ? 'animate-reveal-scope' : '' }}">
        @svg(
            match ($block->style) {
                'light' => 'star',
                'light_alt' => 'spiral',
                default => 'arrow',
            },
            'size-16 absolute right-full -translate-x-8'
        )
        <div
            class="relative  grid items-center gap-8 overflow-hidden lg:grid-cols-2
      {{ match ($block->style) {
          'light' => 'bg-blue/10 rounded-xl',
          'light_alt' => 'bg-green/10 rounded-xl',
          default => 'rounded-3xl has-black-background-color',
      } }}
      
      ">
            <div
                class="{{ $image_side === 'left' ? 'lg:order-first' : 'lg:order-last' }} {{ $image ? 'relative h-full min-h-64' : 'p-6 lg:p-10' }}">
                @if ($image)
                    @php
                        $photo = wp_get_attachment_image($image, 'large', false, [
                            'class' => $animate ? 'animate-reveal-up' : '',
                            'sizes' => '(min-width: 1024px) 40vw, 90vw',
                        ]);
                    @endphp

                    {{-- Photo fills its half of the panel, flush to the edges. When
                     animating, a mask hugging the image keeps it out of sight
                     at translateY(100%). --}}
                    @if ($animate)
                        <div class="overflow-hidden">{!! $photo !!}</div>
                    @else
                        {!! $photo !!}
                    @endif
                @endif
            </div>

            <div class="p-6 xl:p-12 pt-0 prose  {{ $light ? '' : ' prose-invert' }}">
                <InnerBlocks template="{{ $block->template }}" />
            </div>
        </div>

        @if ($light)
            <div
                class="border-yellow pointer-events-none absolute inset-0 rotate-2 rounded-xl border-2 transition group-hover:rotate-0">
            </div>
        @else
            @svg('paperplane', 'pointer-events-none absolute -top-6 right-10 w-20 text-yellow')
        @endif
    </section>

    @unless ($block->preview)
    </div>
@endunless
