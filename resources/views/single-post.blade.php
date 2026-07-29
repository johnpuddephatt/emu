@extends('layouts.app')

@section('content')
    @while (have_posts())
        @php(the_post())
        <article @php(post_class('h-entry'))>
            <header class="has-black-background-color relative overflow-x-clip">
                <div class="relative mx-auto max-w-content px-4 pt-28 text-center lg:pt-32 {{ has_post_thumbnail() ? 'pb-24 lg:pb-32' : 'pb-16 lg:pb-20' }}">
                    @if ($category = get_the_category()[0] ?? null)
                        <p class="text-yellow mb-4 text-sm font-bold">
                            <a
                                href="{{ get_category_link($category) }}"
                                class="no-underline hover:underline"
                            >{{ $category->name }}</a>
                        </p>
                    @endif

                    <h1 class="p-name type-xl text-balance">{!! get_the_title() !!}</h1>

                    <div class="mt-8 flex flex-wrap items-center justify-center gap-x-3 gap-y-2 text-sm">
                        <x-author />
                        <span aria-hidden="true">&middot;</span>
                        <time class="dt-published" datetime="{{ get_post_time('c', true) }}">{{ get_the_date() }}</time>
                    </div>
                </div>

                <a
                    href="{{ home_url('/') }}"
                    class="absolute top-4 left-4 z-30 lg:top-6 lg:left-6"
                    aria-label="{{ $siteName }}"
                >
                    @svg('logo', 'h-auto w-24 fill-white lg:w-28')
                </a>
            </header>

            @if (has_post_thumbnail())
                <div class="max-w-wide relative z-10 mx-auto -mt-14 px-4 lg:-mt-20">
                    {!!
                        get_the_post_thumbnail(null, 'large', [
                            'class' => 'aspect-[16/9] w-full rounded-xl object-cover',
                            'sizes' => '(min-width: 800px) 48rem, 95vw',
                        ])
                    !!}
                </div>
            @endif

            <div class="prose lg:prose-lg max-w-content! page-content py-12 lg:py-16">
                @php(the_content())
            </div>
        </article>
    @endwhile
@endsection
