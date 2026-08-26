    <a href="{{ get_permalink() }}" class="group flex flex-col">

        <div class="overflow-hidden  rounded-lg">
            @if (has_post_thumbnail())
                {!! get_the_post_thumbnail(null, 'medium_large', [
                    'class' => 'aspect-[4/3] w-full object-cover group-hover:scale-105 transition duration-1000',
                    'sizes' => '(min-width: 1024px) 30vw, 90vw',
                ]) !!}
            @else
                <div
                    class="bg-cream flex aspect-[4/3] w-full items-center justify-center  group-hover:scale-105 transition duration-1000">
                    @svg('logo-shape', 'h-auto w-16 text-yellow-dark opacity-60')
                </div>
            @endif
        </div>

        {{-- Search results reuse this card for pages, which have no author —
             don't leave the separator dangling. --}}
        <p class="text-gray mt-4 mb-0 text-sm">
            <time class="dt-published" datetime="{{ get_post_time('c', true) }}">{{ get_the_date() }}</time>
            @if ($author = get_the_author())
                &middot; {{ $author }}
            @endif
        </p>

        <h3 class="mt-1 text-2xl font-bold">
            {!! $title !!}
        </h3>

        @if (get_the_excerpt())
            <p class="entry-summary mt-2 mb-0 text-sm leading-relaxed">{!! get_the_excerpt() !!}</p>
        @endif

    </a>
