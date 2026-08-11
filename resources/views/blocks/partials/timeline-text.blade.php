<div
    {{-- `.rich-text`, not `.prose`: the timeline section is `.not-prose`,
         which the typography plugin's selectors can never re-enter. --}}
    class="rich-text w-full max-w-sm rounded-xl p-8 xl:p-12 {{ $color['tint'] }} {{ $flip ? 'rotate-1 ' : 'ml-auto -rotate-1' }}">
    {!! $item['text'] !!}
</div>
