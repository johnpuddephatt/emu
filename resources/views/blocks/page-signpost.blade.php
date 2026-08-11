@unless ($block->preview)
    <div {{ $attributes }}>
@endunless

@php
    // Same brand cycle as the timeline cards, so a run of signposts down a
    // page never repeats the same tint twice in a row.
    $palette = [
        ['tint' => 'bg-yellow-soft'],
        ['tint' => 'bg-pink-soft'],
        ['tint' => 'bg-green-soft'],
        ['tint' => 'bg-blue-soft'],
        ['tint' => 'bg-red-soft'],
    ];
@endphp

<section class="wp-block alignfull relative py-12 lg:py-16">
    <div class="max-w-wide mx-auto px-4">
        <div class="prose max-w-content">
            <InnerBlocks template="{{ $block->template }}" />
        </div>

        @if ($cards)
            <ul
                class="not-prose mt-10 grid list-none gap-8 p-0 lg:gap-10
                    {{ [1 => '', 2 => 'sm:grid-cols-2', 3 => 'sm:grid-cols-2 lg:grid-cols-3'][$columns] ?? 'sm:grid-cols-2 lg:grid-cols-3' }}"
            >
                @foreach ($cards as $card)
                    <li class="flex">
                        @include('blocks.partials.signpost-card', [
                            'card' => $card,
                            'flip' => $loop->even,
                            'color' => $palette[$loop->index % count($palette)],
                        ])
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</section>

@unless ($block->preview)
    </div>
@endunless
