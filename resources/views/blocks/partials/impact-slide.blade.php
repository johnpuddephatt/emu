@if ($slide['image'])
    {!!
        wp_get_attachment_image($slide['image'], 'large', false, [
            'class' => 'aspect-[4/5] w-full object-cover',
            'sizes' => '(min-width: 1280px) 20vw, (min-width: 640px) 40vw, 80vw',
        ])
    !!}
@else
    <div class="bg-gray-light aspect-[4/5] w-full"></div>
@endif

<figcaption class="mt-3">
    <strong class="block text-xl font-bold">{{ $slide['stat'] }}</strong>
    @if ($slide['caption'])
        <span class="text-sm">{{ $slide['caption'] }}</span>
    @endif
</figcaption>
