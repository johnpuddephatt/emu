@php
    $light = $block->style === 'light' || $block->style === 'light_alt';

    // The editor iframe scrolls independently, so the scroll-driven reveal
    // would leave the image parked off-screen — render it static there.
    $animate = $image && $animate_image && !$block->preview;
@endphp

@if ($block->style === 'compact')
    {{-- Thin signpost strip: heading + optional description on the left,
         buttons on the right, no image. Layout lives in
         css/common/blocks/cta-panel.css (.cta-compact) because the prose
         heading sizes are unlayered and would beat any utility here. --}}
    @unless ($block->preview)
        <div {{ $attributes }}>
    @endunless

    <section
        class="wp-block cta-compact relative my-8 {{ $block->block->align === 'full' ? 'alignfull px-4' : 'alignwide' }}">
        <div class="has-black-background-color prose prose-invert max-w-none rounded-xl px-6 py-5 lg:px-8">
            <InnerBlocks template="{{ $block->template }}" />
        </div>

        @svg('loop', 'pointer-events-none absolute top-1/2 right-full w-14 -translate-x-4 -translate-y-1/2 text-yellow max-lg:hidden')
    </section>

    @unless ($block->preview)
        </div>
    @endunless
@else
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
                class="{{ $image_side === 'left' ? 'lg:order-first' : 'lg:order-last' }} {{ $image ? ($light ? 'relative h-full min-h-64' : 'relative flex min-h-64 items-end justify-center') : 'p-6 lg:p-10' }}">
                @if ($image)
                    @php
                        // A dark panel's illustration is sized by its own aspect
// ratio, so a portrait one stretches the panel far past
// what the text needs and leaves it ringed with dead
// space. Cap its height and stand it on the panel floor.
$photo = wp_get_attachment_image($image, 'large', false, [
    'class' => trim(
        ($animate ? 'animate-reveal-up ' : '') .
            ($light ? '' : 'max-h-72 w-auto object-contain lg:max-h-128'),
    ),
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
@endif
