<blockquote class="m-0 w-full bg-green-soft p-8 xl:p-12 rounded-xl border-0  {{ $flip ? 'rotate-1' : '-rotate-1' }}">
  @svg('quote', 'h-6 w-auto text-green')

  <p class="mt-4 mb-0 text-xl font-bold leading-snug">{{ $item['quote'] }}</p>

  @if ($item['citation'])
    <cite class="mt-3 block text-sm not-italic">{{ $item['citation'] }}</cite>
  @endif
</blockquote>
