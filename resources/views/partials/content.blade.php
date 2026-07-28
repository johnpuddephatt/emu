<article @php(post_class('flex flex-col'))>
  <a href="{{ get_permalink() }}" class="block">
    @if (has_post_thumbnail())
      {!! get_the_post_thumbnail(null, 'medium_large', [
          'class' => 'aspect-[4/3] w-full rounded-lg object-cover',
          'sizes' => '(min-width: 1024px) 30vw, 90vw',
      ]) !!}
    @else
      <div class="flex aspect-[4/3] w-full items-center justify-center rounded-lg bg-cream">
        @svg('logo-shape', 'h-auto w-16 text-yellow-dark opacity-60')
      </div>
    @endif
  </a>

  <p class="mt-4 mb-0 text-sm text-gray">
    <time class="dt-published" datetime="{{ get_post_time('c', true) }}">{{ get_the_date() }}</time>
    &middot; {{ get_the_author() }}
  </p>

  <h3 class="mt-1 text-2xl font-bold">
    <a href="{{ get_permalink() }}" class="no-underline hover:underline">
      {!! $title !!}
    </a>
  </h3>

  @if (get_the_excerpt())
    <p class="entry-summary mt-2 mb-0 text-sm leading-relaxed">{{ get_the_excerpt() }}</p>
  @endif
</article>
