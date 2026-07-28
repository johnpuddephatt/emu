@php
  $sizeClass = match ($size) {
      'sm' => 'w-10',
      'lg' => 'w-28',
      default => 'w-16',
  };

  $alignClass = match ($align) {
      'center' => 'justify-center',
      'right' => 'justify-end',
      default => 'justify-start',
  };

  $colorClass = match ($color) {
      'black' => 'svg-recolor text-black',
      'yellow' => 'svg-recolor text-yellow-dark',
      'pink' => 'svg-recolor text-pink',
      'green' => 'svg-recolor text-green',
      'blue' => 'svg-recolor text-blue',
      'red' => 'svg-recolor text-red',
      default => '',
  };
@endphp

@unless ($block->preview)
  <div {{ $attributes }}>
@endunless

<figure class="wp-block m-0 flex {{ $alignClass }}">
  @if ($icon)
    @svg($icon, "pointer-events-none h-auto {$sizeClass} {$colorClass}", $rotate ? ['style' => "rotate: {$rotate}deg"] : [])
  @endif
</figure>

@unless ($block->preview)
  </div>
@endunless
