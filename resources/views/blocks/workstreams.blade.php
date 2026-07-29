@unless ($block->preview)
    <div {{ $attributes }}>
    @endunless

    @if ($block->style === 'rows')
        <section class="wp-block alignfull relative py-16 lg:py-24">
            <div class="max-w-wide mx-auto px-4">
                <div class="prose max-w-content mx-auto text-center">
                    <InnerBlocks template="{{ $block->template }}" />
                </div>

                @if ($cards)
                    <ul class="mx-auto mt-12 flex max-w-6xl list-none flex-col gap-8 p-0">
                        @foreach ($cards as $card)
                            <li
                                class="flex flex-col gap-6 rounded-xl border-2 border-yellow-dark bg-white p-6 text-black sm:p-10 lg:items-center lg:gap-16 {{ $loop->even ? 'lg:flex-row-reverse' : 'lg:flex-row' }}">
                                <div class="flex-1">
                                    <h3 class="text-2xl font-bold lg:text-3xl">{!! $card['title'] !!}</h3>

                                    @if ($card['text'])
                                        <p class="mt-4 max-w-prose leading-relaxed">{{ $card['text'] }}</p>
                                    @endif

                                    @if ($card['link'])
                                        <a href="{{ $card['link']['url'] }}"
                                            @if (!empty($card['link']['target'])) target="{{ $card['link']['target'] }}" rel="noopener" @endif
                                            class="wp-element-button mt-6 inline-block !px-4 !py-1.5 !text-xs">
                                            {{ $card['link']['title'] ?: 'Read more' }}
                                        </a>
                                    @endif
                                </div>

                                @if ($card['illustration'])
                                    {!! wp_get_attachment_image($card['illustration'], 'large', false, [
                                        'class' => 'mx-auto h-48 w-auto shrink-0 object-contain lg:h-64',
                                    ]) !!}
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </section>
    @else
        <section class="wp-block alignfull has-black-background-color relative py-16 lg:py-24">
            <div class="max-w-wide mx-auto px-4">
                <div class="prose prose-invert max-w-content mx-auto text-center">
                    <InnerBlocks template="{{ $block->template }}" />
                </div>

                @if ($cards)
                    <div class="relative mt-12">
                        <ul
                            class="relative grid list-none gap-4 p-0 sm:grid-cols-2 lg:grid-cols-3 lg:gap-6 xl:grid-cols-5">
                            @foreach ($cards as $card)
                                <li class="group relative flex flex-col rounded-xl bg-white pt-2 p-5 text-black">
                                    <div aria-hidden="true"
                                        class="border-yellow pointer-events-none absolute inset-0 rotate-3 rounded-xl border-2 transition group-hover:rotate-0">
                                    </div>

                                    @if ($card['illustration'])
                                        {!! wp_get_attachment_image($card['illustration'], 'medium', false, [
                                            'class' => 'mx-auto group-hover:scale-120 duration-500 transition  origin-bottom h-36 w-auto object-contain',
                                        ]) !!}
                                    @endif

                                    <h3 class="mt-4 text-xl font-bold">{!! $card['title'] !!}</h3>

                                    @if ($card['text'])
                                        <p class="mt-2 mb-4 text-sm leading-snug">{{ $card['text'] }}</p>
                                    @endif

                                    @if ($card['link'])
                                        <a href="{{ $card['link']['url'] }}"
                                            @if (!empty($card['link']['target'])) target="{{ $card['link']['target'] }}" rel="noopener" @endif
                                            class="wp-element-button mt-auto self-start !px-4 !py-1.5 !text-xs before:absolute before:inset-0">
                                            {{ $card['link']['title'] ?: 'Read more' }}
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </section>
    @endif

    @unless ($block->preview)
    </div>
@endunless
