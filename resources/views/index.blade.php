@extends('layouts.app')

@section('content')
    @include('partials.page-header')

    <div class="max-w-wide mx-auto px-4 py-16 md:px-8 lg:py-24">
        @if (! have_posts())
            <x-alert type="warning"> {!! __('Sorry, no results were found.', 'sage') !!} </x-alert>

            {!! get_search_form(false) !!}
        @endif

        <div class="grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
            @while (have_posts())
                @php(the_post())
                @includeFirst(['partials.content-' . get_post_type(), 'partials.content'])
            @endwhile
        </div>

        {{-- WP runs the pagination `class` arg through sanitize_html_class(),
             so it can only take one class — spacing goes on a wrapper. --}}
        @if ($pagination = get_the_posts_pagination([
            'mid_size' => 2,
            'prev_text' => __('&larr; Newer', 'sage'),
            'next_text' => __('Older &rarr;', 'sage'),
            'class' => 'pagination',
        ]))
            <div class="mt-16">
                {!! $pagination !!}
            </div>
        @endif
    </div>
@endsection
