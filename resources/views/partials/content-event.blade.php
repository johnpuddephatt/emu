@php
    $start = ($value = get_field('start')) ? strtotime($value) : null;
    $location = get_field('location');
@endphp

<article @php(post_class('flex flex-col'))>
    <a href="{{ get_permalink() }}" class="block">
        @if (has_post_thumbnail())
            {!!
                get_the_post_thumbnail(null, 'medium_large', [
                    'class' => 'aspect-[4/3] w-full rounded-lg object-cover',
                    'sizes' => '(min-width: 1024px) 30vw, 90vw',
                ])
            !!}
        @else
            <div class="bg-cream flex aspect-[4/3] w-full items-center justify-center rounded-lg">
                @svg('logo-shape', 'h-auto w-16 text-yellow-dark opacity-60')
            </div>
        @endif
    </a>

    @if ($start)
        <p class="text-gray mt-4 mb-0 text-sm">
            <time datetime="{{ wp_date('c', $start) }}">{{ wp_date('l j F Y, g:ia', $start) }}</time>
            @if ($location)
                &middot; {{ $location }}
            @endif
        </p>
    @elseif ($location)
        <p class="text-gray mt-4 mb-0 text-sm">{{ $location }}</p>
    @endif

    <h3 class="mt-1 text-2xl font-bold">
        <a href="{{ get_permalink() }}" class="no-underline hover:underline"> {!! $title !!} </a>
    </h3>

    @if (get_the_excerpt())
        <p class="entry-summary mt-2 mb-0 text-sm leading-relaxed">{{ get_the_excerpt() }}</p>
    @endif
</article>
