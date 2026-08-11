@unless ($block->preview)
    <div {{ $attributes }}>
@endunless

{{--
    Nothing loads from the video provider until the modal is opened: the still
    is our own image, and the player URL is only written to the iframe on click.

    No arrow functions in the Alpine attributes below — `>` ends the tag as far
    as wptexturize is concerned, which silently breaks the component.
--}}
<div
    class="not-prose group bg-gray-light relative isolate aspect-video w-full overflow-hidden"
    x-data="{
        play() {
            @if ($embed)
                this.$refs.frame.src = '{{ $embed }}';
            @else
                this.$refs.player.innerHTML = this.$refs.markup.innerHTML;
            @endif

            this.$refs.dialog.showModal();
        },
        stop() {
            @if ($embed)
                this.$refs.frame.src = '';
            @else
                this.$refs.player.innerHTML = '';
            @endif
        },
    }"
>
    @if ($thumbnail)
        {!!
            wp_get_attachment_image($thumbnail, 'large', false, [
                'class' => 'absolute inset-0 size-full object-cover',
                'sizes' => '(min-width: 1024px) 50vw, 100vw',
            ])
        !!}
    @elseif ($thumbnail_url)
        <img src="{{ esc_url($thumbnail_url) }}" alt="" loading="lazy" class="absolute inset-0 size-full object-cover" />
    @endif

    <span aria-hidden="true" class="absolute inset-0 z-10 flex items-center justify-center">
        @svg('play', 'w-1/5 max-w-32 text-white/70 drop-shadow-lg transition group-hover:scale-110 group-hover:text-white/90')
    </span>

    @if ($title || $description)
        <div class="absolute inset-x-0 bottom-0 z-10 p-4 lg:p-6">
            @if ($title)
                <h3 class="m-0 leading-loose">
                    <span class="type-sm box-decoration-clone bg-black px-3 py-1.5 text-white">{{ $title }}</span>
                </h3>
            @endif

            @if ($description)
                <p class="m-0 mt-2 max-w-sm leading-loose">
                    <span class="box-decoration-clone bg-black px-3 py-1.5 text-sm font-bold text-white">
                        {{ $description }}
                    </span>
                </p>
            @endif
        </div>
    @endif

    {{-- Stretched over the whole still so the caption stays outside the button --}}
    <button
        type="button"
        class="absolute inset-0 z-20 size-full cursor-pointer"
        aria-label="{{ $title ? sprintf(__('Play video: %s', 'sage'), $title) : __('Play video', 'sage') }}"
        @click="play()"
    ></button>

    <dialog
        x-ref="dialog"
        class="m-0 h-screen max-h-none w-screen max-w-none bg-black p-0 backdrop:bg-black"
        {{-- stop() is called on the way out of every path we control, not just
             via @close, so playback can never outlive the modal. --}}
        @close="stop()"
        @click="if (event.target === $el) { stop(); $el.close(); }"
    >
        <div class="relative flex size-full items-center justify-center p-4 lg:p-10">
            <button
                type="button"
                class="absolute top-3 right-3 z-10 cursor-pointer rounded-full p-2 text-white hover:bg-white/10 lg:top-6 lg:right-6"
                aria-label="{{ __('Close', 'sage') }}"
                @click="stop(); $refs.dialog.close()"
            >
                @svg('close', 'size-7')
            </button>

            @if ($embed)
                <iframe
                    x-ref="frame"
                    src=""
                    title="{{ $title ?: __('Video', 'sage') }}"
                    {{-- Fills the screen: width is capped by what 16:9 allows
                         at the available height, so it never overflows. --}}
                    class="aspect-video max-h-full w-full max-w-[calc((100vh-5rem)*16/9)]"
                    allow="autoplay; fullscreen; picture-in-picture; encrypted-media"
                    allowfullscreen
                ></iframe>
            @else
                <div
                    x-ref="player"
                    class="aspect-video max-h-full w-full max-w-[calc((100vh-5rem)*16/9)] [&_iframe]:size-full"
                ></div>

                <template x-ref="markup">{!! $html !!}</template>
            @endif
        </div>
    </dialog>
</div>

@unless ($block->preview)
    </div>
@endunless
