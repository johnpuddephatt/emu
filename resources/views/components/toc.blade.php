@props([
    'items' => [],
    'title' => 'In this section',
])

@if ($items)
    <nav
        {{ $attributes->class('has-black-background-color rounded-xl px-6 py-6 sm:px-10 lg:py-8') }}
        aria-label="{{ $title }}"
    >
        <h2 class="text-base font-bold">{{ $title }}</h2>

        <ul class="mt-4 flex list-none flex-col flex-wrap gap-3 p-0 sm:flex-row sm:gap-x-8">
            @foreach ($items as $id => $label)
                <li>
                    <a
                        href="#{{ $id }}"
                        class="text-sm font-bold underline decoration-2 underline-offset-4 transition-all hover:underline-offset-8"
                    >
                        {{ $label }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
@endif
