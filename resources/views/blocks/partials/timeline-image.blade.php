@if ($item['image'])
  <figure class="m-0 w-full {{ $flip ? 'rotate-2' : '-rotate-2' }}">
    {{--
      w-auto/h-auto override the img element's width/height attributes so
      the max constraints scale the image preserving its aspect ratio:
      whichever side is longest stops at 24rem. Centred on mobile, hugging
      the spine on large screens.
    --}}
    {!! wp_get_attachment_image($item['image'], 'large', false, [
        'class' => 'mx-auto h-auto w-auto max-h-[28rem] max-w-[min(28rem,100%)] rounded-lg ' .
            ($flip ? 'lg:mr-auto lg:ml-0' : 'lg:ml-auto lg:mr-0'),
        'sizes' => '(min-width: 640px) 28rem, 90vw',
    ]) !!}
  </figure>
@endif
