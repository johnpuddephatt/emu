<div class="relative w-full {{ $flip ? 'rotate-1' : '-rotate-1' }}">
    <div
        aria-hidden="true"
        class="pointer-events-none z-10 absolute inset-0 rounded-xl border-2 border-yellow {{ $flip ? 'rotate-2' : '-rotate-2' }}"
    ></div>

    <div class="relative overflow-hidden rounded-xl {{ $color['tint'] }} ">
        @if ($item['image'])
            {!!
                wp_get_attachment_image($item['image'], 'large', false, [
                    'class' => 'w-full max-h-64 object-cover',
                    'sizes' => '(min-width: 1024px) 40vw, 90vw',
                ])
            !!}
        @endif

        <div class="p-8 xl:p-12">
            @if ($item['title'])
                <h3 class="text-base font-bold">{{ $item['title'] }}</h3>
            @endif

            @if ($item['text'])
                <p class="mt-2 mb-0 text-sm leading-relaxed">{{ $item['text'] }}</p>
            @endif
        </div>
    </div>
</div>
