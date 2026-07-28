@unless ($block->preview)
  <div {{ $attributes }}>
@endunless

<figure class="mx-auto max-w-2xl">
  <blockquote class="border-l-4 border-yellow pl-6 text-xl italic">
    <p>{{ $quote }}</p>
  </blockquote>

  @if ($author)
    <figcaption class="mt-6 flex items-center gap-x-4 pl-6">
      @if ($avatar)
        {!! wp_get_attachment_image($avatar, 'thumbnail', false, [
          'class' => 'size-12 rounded-full object-cover',
        ]) !!}
      @endif

      <div>
        <cite class="block text-sm font-bold not-italic">
          {{ $author }}
        </cite>

        @if ($role)
          <span class="text-sm text-gray">{{ $role }}</span>
        @endif
      </div>
    </figcaption>
  @endif
</figure>

@unless ($block->preview)
  </div>
@endunless
