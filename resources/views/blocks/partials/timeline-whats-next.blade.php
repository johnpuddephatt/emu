<div class="rounded-3xl max-w-content mx-auto bg-blue-soft relative z-10 p-12 xl:p-16">
  @if ($item['title'])
    <h3 class="text-2xl font-bold">{{ $item['title'] }}</h3>
  @endif

  @if ($item['text'])
    <div class="prose mt-6 max-w-none">{!! $item['text'] !!}</div>
  @endif

  @if ($item['link'])
    <a
      href="{{ $item['link']['url'] }}"
      @if (!empty($item['link']['target'])) target="{{ $item['link']['target'] }}" rel="noopener" @endif
      class="wp-element-button mt-4 !px-4 !py-1.5 !text-xs"
    >
      {{ $item['link']['title'] ?: 'Find out more' }}
    </a>
  @endif
</div>
