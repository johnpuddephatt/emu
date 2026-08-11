<div class="max-w-content bg-blue-soft relative z-10 mx-auto rounded-3xl p-12 xl:p-16">
    @if (!empty($item['title']))
        <h3 class="text-2xl font-bold">{{ $item['title'] }}</h3>
    @endif

    @if (!empty($item['text']))
        {{-- `.rich-text`, not `.prose`: the timeline section is `.not-prose`,
             which the typography plugin's selectors can never re-enter. --}}
        <div class="rich-text mt-6">{!! $item['text'] !!}</div>
    @endif

    @if (!empty($item['link']))
        <a
            href="{{ $item['link']['url'] }}"
            @if (!empty($item['link']['target'])) target="{{ $item['link']['target'] }}" rel="noopener" @endif
            class="wp-element-button mt-4 !px-4 !py-1.5 !text-xs"
        >
            {{ $item['link']['title'] ?: 'Find out more' }}
        </a>
    @endif
</div>
