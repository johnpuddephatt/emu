@unless ($block->preview)
    <div {{ $attributes }}>
@endunless

<section class="wp-block alignfull has-black-background-color relative py-12 lg:py-20">
    <div class="max-w-wide mx-auto px-4">
        <div class="prose prose-invert max-w-content">
            <InnerBlocks template="{{ $block->template }}" />
        </div>

        @if ($episodes)
            {{-- Arrow functions are off limits in Alpine attributes here: the `>`
                 ends the tag as far as wptexturize is concerned, and it then
                 curly-quotes the rest of the attribute into a syntax error. --}}
            <div
                class="not-prose mt-8 max-w-xl"
                x-data="{
                    current: null,
                    playing: false,
                    toggle(url) {
                        var player = this.$refs.player;

                        // Switching episodes always starts the new one; clicking
                        // the episode already loaded toggles play/pause.
                        if (this.current !== url) {
                            this.current = url;
                            player.src = url;
                            player.play().catch(function () {});

                            return;
                        }

                        if (this.playing) {
                            player.pause();
                        } else {
                            player.play().catch(function () {});
                        }
                    },
                }"
            >
                <audio
                    x-ref="player"
                    @play="playing = true"
                    @pause="playing = false"
                    @ended="playing = false"
                    preload="none"
                    class="hidden"
                ></audio>

                <ul class="m-0 max-h-80 list-none overflow-y-auto p-0">
                    @foreach ($episodes as $episode)
                        <li class="border-gray/50 border-b border-dotted last:border-0">
                            @if ($episode['upcoming'] || ! $episode['audio'])
                                <div class="flex items-center gap-3 px-2 py-3">
                                    <span class="bg-green shrink-0 px-2 py-1 text-xs leading-none text-white">
                                        {{ __('Coming soon', 'sage') }}
                                    </span>

                                    <span class="truncate">{{ $episode['title'] }}</span>
                                </div>
                            @else
                                <button
                                    type="button"
                                    class="hover:bg-gray/30 flex w-full cursor-pointer items-center gap-3 px-2 py-3 text-left transition"
                                    @click="toggle('{{ $episode['audio'] }}')"
                                    :aria-pressed="current === '{{ $episode['audio'] }}' && playing"
                                >
                                    <span
                                        class="shrink-0"
                                        x-show="! (current === '{{ $episode['audio'] }}' && playing)"
                                    >
                                        @svg('play-circle', 'size-7')
                                    </span>

                                    <span
                                        x-cloak
                                        class="shrink-0"
                                        x-show="current === '{{ $episode['audio'] }}' && playing"
                                    >
                                        @svg('pause-circle', 'size-7')
                                    </span>

                                    {{-- Episode numbers restart each season, so the
                                         season has to come with them to make sense. --}}
                                    @if ($episode['number'])
                                        <span
                                            class="bg-green min-w-14 shrink-0 px-2 py-1 text-center text-sm leading-none whitespace-nowrap text-white"
                                        >
                                            @if ($episode['season'])
                                                S{{ $episode['season'] }}
                                            @endif

                                            E{{ $episode['number'] }}
                                        </span>
                                    @endif

                                    <span class="truncate">{{ $episode['title'] }}</span>

                                    @if ($episode['duration'])
                                        <span class="text-gray ml-auto shrink-0 text-sm">
                                            ({{ $episode['duration'] }})
                                        </span>
                                    @endif
                                </button>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($links)
            <div class="mt-10 flex flex-wrap items-center gap-3">
                <span class="text-sm">{{ __('Listen on:', 'sage') }}</span>

                @foreach ($links as $row)
                    @if ($link = $row['link'] ?? null)
                        <a
                            href="{{ $link['url'] }}"
                            @if (!empty($link['target'])) target="{{ $link['target'] }}" rel="noopener" @endif
                            class="wp-element-button"
                        >{{ $link['title'] }}</a>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</section>

@unless ($block->preview)
    </div>
@endunless
