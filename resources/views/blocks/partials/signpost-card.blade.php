{{--
    A page signpost card: the timeline card's offset hand-drawn border and
    tinted panel, with a bigger title and a "read more" button. The button's
    stretched pseudo-element makes the whole card clickable.
--}}
<div class="group relative flex h-full w-full {{ $flip ? 'rotate-1' : '-rotate-1' }}">
    <div aria-hidden="true"
        class="pointer-events-none absolute inset-0 z-10 rounded-xl border-2 border-yellow transition group-hover:rotate-0 {{ $flip ? 'rotate-2' : '-rotate-2' }}">
    </div>

    <div class="relative flex w-full flex-col overflow-hidden rounded-xl {{ $color['tint'] }}">
        {{-- Full bleed across the top of the card, cropped to 2:1. The
             parent's overflow-hidden is what rounds off the top corners. --}}
        @if ($card['image'])
            {!! wp_get_attachment_image($card['image'], 'large', false, [
                'class' => 'aspect-[2] w-full object-cover',
                'sizes' => '(min-width: 640px) 45vw, 90vw',
            ]) !!}
        @endif

        <div class="flex flex-1 flex-col p-6 lg:p-8">
            @if ($card['title'])
                <h3 class="type-md">{{ $card['title'] }}</h3>
            @endif

            @if ($card['text'])
                <p class="mt-3 mb-0 text-sm leading-relaxed">{{ $card['text'] }}</p>
            @endif

            {{-- mt-auto so the buttons line up across a row of cards --}}
            <div class="mt-auto pt-6">
                <a href="{{ $card['url'] }}"
                    class="wp-element-button inline-block !px-4 !py-1.5 !text-xs before:absolute before:inset-0">
                    {{ $card['label'] }}
                </a>
            </div>
        </div>
    </div>
</div>
