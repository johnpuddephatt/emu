@extends('layouts.app')

@section('content')
    @include('partials.page-header')

    <div class="max-w-wide mx-auto px-4 py-16 md:px-8 lg:py-24">
        <div class="max-w-content">
            {!! get_search_form(false) !!}
        </div>

        @if (! have_posts())
            <x-alert type="caution" class="mt-8 max-w-content">
                {!! __('Sorry, no results were found.', 'sage') !!}
            </x-alert>
        @else
            <div class="mt-12 grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
                {{-- Results can be any public post type, and only `content` and
                     `content-event` are cards — `content-page` renders a whole
                     document — so the card is picked explicitly here rather
                     than by post type. --}}
                @while (have_posts())
                    @php(the_post())
                    @include(get_post_type() === 'event' ? 'partials.content-event' : 'partials.content')
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
        @endif
    </div>
@endsection
