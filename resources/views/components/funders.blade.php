{{--
    Logo groups are assembled by App\View\Composers\Funders: workstream
    supporters (on their workstreams' pages only), then core funders, then
    memberships and accreditations — the last as a smaller, quieter row.
--}}
@if ($groups->isNotEmpty())
    <section class="funders bg-white" aria-label="{{ __('Funders and supporters', 'sage') }}">
        <div class="max-w-wide mx-auto divide-y divide-black/10 px-4 md:px-6 lg:px-8 xl:px-12">
            @foreach ($groups as $group)
                <div
                    @class([
                        'funders__group py-10 lg:py-14',
                        "funders__group--{$group['key']}",
                    ])
                >
                    <h2
                        @class([
                            'text-center text-sm font-bold tracking-wide uppercase',
                            'sr-only' => ! $group['heading'],
                        ])
                    >
                        {{ $group['heading'] ?: $group['fallback'] }}
                    </h2>

                    <ul class="mt-8 flex list-none flex-wrap items-center justify-center gap-x-10 gap-y-8 p-0 lg:gap-x-14">
                        @foreach ($group['logos'] as $funder)
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
            @endforeach
        </div>
    </section>
@endif
