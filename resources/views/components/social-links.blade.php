@props([
    'iconClass' => 'size-6',
])

@php
    $platforms = config('social.platforms');

    // Skip rows without a link, or with a platform that has no icon to
    // draw — a half-filled row in the admin shouldn't fatal the front end.
    $accounts = collect(get_field('social_media', 'option') ?: [])
        ->filter(fn ($account) => ! empty($account['url']) && isset($platforms[$account['platform'] ?? '']))
        ->all();
@endphp

@if ($accounts)
    <ul {{ $attributes->merge(['class' => 'flex list-none flex-wrap items-center gap-5 p-0']) }}>
        @foreach ($accounts as $account)
            <li>
                <a
                    href="{{ $account['url'] }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="hover:text-yellow block no-underline transition duration-300"
                    aria-label="{{ sprintf(__('%1$s on %2$s', 'sage'), get_bloginfo('name'), $platforms[$account['platform']]) }}"
                >
                    @svg('social.' . $account['platform'], $iconClass)
                </a>
            </li>
        @endforeach
    </ul>
@endif
