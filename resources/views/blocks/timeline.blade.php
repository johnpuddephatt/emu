@unless ($block->preview)
    <div {{ $attributes }}>
    @endunless

    @php
        // Brand colours cycled by item index. Full class names so Tailwind
        // picks them up: accent = borders, tint = pale backgrounds, ink = text.
        $palette = [
            ['accent' => 'border-yellow', 'tint' => 'bg-yellow-soft', 'ink' => 'text-yellow-dark'],
            ['accent' => 'border-pink', 'tint' => 'bg-pink-soft', 'ink' => 'text-pink'],
            ['accent' => 'border-green', 'tint' => 'bg-green-soft', 'ink' => 'text-green'],
            ['accent' => 'border-blue', 'tint' => 'bg-blue-soft', 'ink' => 'text-blue'],
            ['accent' => 'border-red', 'tint' => 'bg-red-soft', 'ink' => 'text-red'],
        ];
    @endphp

    <section class="not-prose wp-block alignfull relative py-16 lg:py-24">
        <div class="relative mx-auto max-w-6xl px-4">
            {{-- The timeline spine --}}
            <div aria-hidden="true" class="bg-yellow-dark absolute top-0 bottom-0 left-1/2 w-3 -translate-x-1/2"></div>

            @if ($intro)
                <div
                    class="rounded-blob has-black-background-color animate-enter-up relative mx-auto mb-10 flex aspect-square w-72 items-center justify-center p-10 text-center text-2xl font-bold text-balance lg:mb-16 lg:w-80 lg:text-3xl">
                    {{ $intro }}
                </div>
            @endif

            @if ($items)
                {{--
        On large screens each item starts on its own grid row and spans
        two, so consecutive items overlap by half and zigzag tightly
        down the spine regardless of their heights.
      --}}
                <ul class="m-0 p-0 lg:grid lg:grid-cols-2 lg:gap-x-24 lg:gap-y-16">
                    @foreach ($items as $item)
                        <li @class([
                            ' before:hidden! relative mx-auto max-w-sm lg:mx-0 lg:max-w-none flex items-center lg:[grid-row:var(--row)/span_2]',
                            'mt-12 lg:mt-0' => !$loop->first,
                            'lg:col-start-2 animate-enter-right' => $loop->even,
                            'animate-enter-left' => !$loop->even,
                        ]) style="--row: {{ $loop->iteration }}">
                            {{-- Marker dot on the spine --}}
                            <span aria-hidden="true" @class([
                                'hidden lg:block absolute top-1/2 size-10 -translate-y-1/2 rounded-full border-2 border-black bg-white',
                                '-right-12 translate-x-1/2' => !$loop->even,
                                '-left-12 -translate-x-1/2' => $loop->even,
                            ])></span>

                            @include(
                                'blocks.partials.timeline-' . str_replace('_', '-', $item['acf_fc_layout']),
                                [
                                    'item' => $item,
                                    'flip' => $loop->even,
                                    'color' => $palette[$loop->index % count($palette)],
                                ]
                            )

                            {{-- Hand-drawn doodles scattered along the outer edges --}}
                            @php(
    $doodle = match ($loop->index % 5) {
        1 => 'star',
        2 => 'spiral',
        0 => 'arrow',
        default => null
    }
)
                            @if ($doodle)
                                @svg(
                                    $doodle,
                                    ' pointer-events-none absolute hidden h-auto w-14 lg:block ' .
                                        ($loop->even ? '-right-4 xl:-right-16 ' : '-left-4 xl:-left-16 ') .
                                        ($doodle === 'star' ? '-bottom-12  text-yellow' . ($loop->even ? 'rotate-12' : '-rotate-12') : '-top-8 ' . ($loop->even ? '-rotate-6' : 'rotate-6')) .
                                        match (($loop->index + 2) % 7) {
                                            1 => ' text-yellow',
                                            2 => ' text-red',
                                            3 => ' text-blue',
                                            4 => ' text-green',
                                            default => ' text-black',
                                        }
                                )
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif

            @if ($whatsNext)
                <div class="animate-enter-up relative mt-12 lg:mt-20">
                    @include('blocks.partials.timeline-whats-next', ['item' => $whatsNext])
                </div>
            @endif
        </div>
    </section>

    @unless ($block->preview)
    </div>
@endunless
