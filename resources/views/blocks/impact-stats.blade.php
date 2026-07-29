@unless ($block->preview)
    <div {{ $attributes }}>
@endunless

<section class="wp-block alignfull relative py-12 lg:py-20">
    <div class="max-w-wide mx-auto px-4">
        <div class="max-w-content prose prose-2xl">
            <InnerBlocks template="{{ $block->template }}" />
        </div>

        @if ($slides)
            @if ($block->preview)
                {{-- Static grid in the editor: Alpine/Swiper don't run there --}}
                <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach (array_slice($slides, 0, 4) as $slide)
                        <figure class="!m-0">
                            @include('blocks.partials.impact-slide', ['slide' => $slide])
                        </figure>
                    @endforeach
                </div>
            @else
                <div
                    x-cloak
                    x-data="{
                        swiper: null,
                        showControls: false,
                        showPreviousControl: true,
                        showNextControl: true,
                        totalSlides: {{ count($slides) }},
                    }"
                    x-init="
                        swiper = new Swiper($refs.container, {
                            loop: false,
                            spaceBetween: 16,
                            slidesPerView: Math.min(totalSlides, 1.25),
                            on: {
                                progress: function () {
                                    showPreviousControl = ! this.isBeginning;
                                    showNextControl = ! this.isEnd;
                                    showControls = ! (this.isBeginning && this.isEnd);
                                },
                            },
                            breakpoints: {
                                640: { slidesPerView: Math.min(totalSlides, 2) },
                                1024: { slidesPerView: Math.min(totalSlides, 3), spaceBetween: 24 },
                                1280: { slidesPerView: Math.min(totalSlides, 4), spaceBetween: 24 },
                            },
                        })
                    "
                >
                    <div class="swiper-container mt-10 w-full" x-ref="container">
                        <div class="swiper-wrapper w-full">
                            @foreach ($slides as $slide)
                                <figure class="swiper-slide !h-auto">
                                    @include('blocks.partials.impact-slide', ['slide' => $slide])
                                </figure>
                            @endforeach
                        </div>
                    </div>

                    <div x-show="showControls" class="mt-8 flex justify-center gap-3">
                        <button
                            type="button"
                            aria-label="{{ __('Previous slides', 'sage') }}"
                            class="bg-pink hover:bg-red flex size-10 items-center justify-center rounded-full text-white transition"
                            x-bind:class="{ 'opacity-25': ! showPreviousControl }"
                            x-bind:disabled="! showPreviousControl"
                            @click="swiper.slidePrev()"
                        >
                            @svg('chevron-left', 'size-5')
                        </button>

                        <button
                            type="button"
                            aria-label="{{ __('Next slides', 'sage') }}"
                            class="bg-pink hover:bg-red flex size-10 items-center justify-center rounded-full text-white transition"
                            x-bind:class="{ 'opacity-25': ! showNextControl }"
                            x-bind:disabled="! showNextControl"
                            @click="swiper.slideNext()"
                        >
                            @svg('chevron-right', 'size-5')
                        </button>
                    </div>
                </div>
            @endif
        @endif
    </div>
</section>

@unless ($block->preview)
    </div>
@endunless
