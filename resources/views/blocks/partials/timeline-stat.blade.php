<div class="mx-auto flex aspect-square w-64 flex-col items-center justify-center rounded-full bg-cream p-8 text-center lg:w-72">
  <span class="text-5xl font-bold">{{ $item['number'] }}</span>

  @if ($item['text'])
    <p class="mt-2 mb-0 text-sm leading-snug">{{ $item['text'] }}</p>
  @endif
</div>
