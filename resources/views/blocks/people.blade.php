@unless ($block->preview)
    <div {{ $attributes }}>
    @endunless

    <section class="wp-block alignwide relative py-12 lg:py-20">
        <div class="max-w-wide mx-auto px-4">
            <div class="prose max-w-content">
                <InnerBlocks template="{{ $block->template }}" />
            </div>

            @if ($people)
                {{-- Bare x-data so the modal's @click handlers below sit inside an
                 Alpine scope; without a component root they never bind. --}}
                <ul x-data
                    class="not-prose mt-10 grid list-none gap-8 p-0 sm:grid-cols-2
                    {{ ['2' => '', '4' => 'lg:grid-cols-4', '3' => 'lg:grid-cols-3'][(string) $columns] ?? 'lg:grid-cols-3' }}">
                    @foreach ($people as $person)
                        @php
                            // Only worth a modal if there's actually a bio behind it.
$modal = $show_modal && $person['bio'];
$id = 'person-' . $person['anchor'];

// Shared by the card and the modal so a person keeps one colour.
$tint = match ($loop->index % 4) {
    0 => 'bg-red-soft',
    1 => 'bg-blue-soft',
    2 => 'bg-green-soft',
    3 => 'bg-yellow-soft',
                            };
                        @endphp

                        <li class="flex flex-col">
                            <div class="aspect-[2] overflow-hidden rounded-xl {{ $tint }}">
                                @if ($person['photo'])
                                    {!! wp_get_attachment_image($person['photo'], 'medium_large', false, [
                                        'class' => 'size-full object-cover object-bottom',
                                        'sizes' => '(min-width: 1024px) 25vw, (min-width: 640px) 50vw, 100vw',
                                    ]) !!}
                                @endif
                            </div>

                            <h3 id="{{ $person['anchor'] }}" class="type-sm mt-4 scroll-mt-10">{{ $person['name'] }}</h3>

                            @if ($person['role'])
                                <p class="mt-1 text-sm">{{ $person['role'] }}</p>
                            @endif

                            @if ($modal)
                                <button type="button" class="wp-element-button mt-4 self-start !px-4 !py-1.5 !text-xs"
                                    aria-haspopup="dialog"
                                    @click="document.getElementById('{{ $id }}').showModal()">{{ __('Read bio', 'sage') }}</button>

                                {{-- Clicking the backdrop counts as a click on the dialog
                                 itself, which is how the outside-click close works. --}}
                                <dialog id="{{ $id }}" aria-label="{{ $person['name'] }}"
                                    {{-- `m-auto` restores the centring the UA stylesheet
                                     gives dialogs, which preflight resets away. --}}
                                    class="max-w-content m-auto max-h-[85vh] w-[90vw] overflow-y-auto rounded-xl p-0 backdrop:bg-black/80"
                                    @click="if (event.target === $el) { $el.close(); }">
                                    {{-- Stacked above the text, not beside it: a narrow column
                                     crops a wide photo down to a sliver. `contain` on the
                                     card's tint rather than `cover`, so a portrait photo
                                     shows whole here instead of being cropped twice. --}}
                                    @if ($person['photo'])
                                        <div class="aspect-[2] {{ $tint }}">
                                            {!! wp_get_attachment_image($person['photo'], 'medium_large', false, [
                                                'class' => 'size-full object-contain object-bottom',
                                                'sizes' => '(min-width: 54rem) 48rem, 90vw',
                                            ]) !!}
                                        </div>
                                    @endif

                                    <form method="dialog" class="relative p-6 sm:p-8">
                                        <button type="submit"
                                            class="hover:bg-gray-light absolute top-4 right-4 cursor-pointer rounded-full p-1"
                                            aria-label="{{ __('Close', 'sage') }}">
                                            @svg('close', 'size-5')
                                        </button>

                                        <h3 class="type-lg mb-0 pb-0 pr-10">{{ $person['name'] }}</h3>

                                        @if ($person['role'])
                                            <p class="mt-1 type-sm font-bold">{{ $person['role'] }}</p>
                                        @endif

                                        {{-- `.rich-text`, not `.prose`: the grid above is `.not-prose`,
                                         which the typography plugin's selectors can never re-enter. --}}
                                        <div class="rich-text mt-4">{!! $person['bio'] !!}</div>
                                    </form>
                                </dialog>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>

    @unless ($block->preview)
    </div>
@endunless
