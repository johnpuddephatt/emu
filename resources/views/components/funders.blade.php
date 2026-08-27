@php
    /*
     * Rows without both a name and a logo are skipped — a half-filled row in
     * the admin shouldn't render an empty box on every page of the site.
     */
    $funders = collect(get_field('funders', 'option') ?: [])
        ->filter(fn ($funder) => ! empty($funder['name']) && ! empty($funder['logo']));

    /*
     * This renders on every page, so pull the attachments and their metadata
     * in one go — left to itself each logo costs two queries below.
     */
    if ($funders->isNotEmpty()) {
        _prime_post_caches($funders->pluck('logo')->all(), false, true);
    }

    $funders = $funders
        ->map(function ($funder) {
            /*
             * Intrinsic size drives the sizing maths in common/_funders.css:
             * the aspect ratio, plus the height multiplier that would give
             * this logo the same area as a square one. The square root is
             * done here rather than in CSS so the strip doesn't depend on
             * `sqrt()` support. SVGs (and anything else WordPress can't
             * measure) come back without dimensions; those fall back to the
             * CSS defaults and are held in by the max-width there.
             */
            [, $width, $height] = array_pad(wp_get_attachment_image_src($funder['logo'], 'full') ?: [], 3, 0);

            $ratio = $width && $height ? $width / $height : null;

            return [
                ...$funder,
                'ratio' => $ratio ? round($ratio, 4) : null,
                'area' => $ratio ? round(1 / sqrt($ratio), 4) : null,
            ];
        });

    $heading = get_field('funders_heading', 'option');
@endphp

@if ($funders->isNotEmpty())
    <section class="funders bg-white" aria-labelledby="funders-heading">
        <div class="max-w-wide mx-auto px-4 py-10 md:px-6 lg:px-8 lg:py-14 xl:px-12">
            <h2
                id="funders-heading"
                @class([
                    'text-center text-sm font-bold tracking-wide uppercase',
                    'sr-only' => ! $heading,
                ])
            >
                {{ $heading ?: __('Funders and supporters', 'sage') }}
            </h2>

            <ul class="mt-8 flex list-none flex-wrap items-center justify-center gap-x-10 gap-y-8 p-0 lg:gap-x-14">
                @foreach ($funders as $funder)
                    <li class="flex items-center">
                        @php
                            $image = wp_get_attachment_image($funder['logo'], 'medium', false, [
                                'alt' => $funder['name'],
                                'class' => 'funders__logo',
                                'loading' => 'lazy',
                                'style' => $funder['ratio']
                                    ? "--logo-ratio: {$funder['ratio']}; --logo-area: {$funder['area']}"
                                    : null,
                            ]);
                        @endphp

                        @if (! empty($funder['url']))
                            <a
                                href="{{ $funder['url'] }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="block no-underline"
                            >{!! $image !!}</a>
                        @else
                            {!! $image !!}
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
@endif
