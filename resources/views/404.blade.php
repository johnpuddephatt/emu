@extends('layouts.app')

@section('content')
    @include('partials.page-header')

    <div class="max-w-content mx-auto px-4 py-16 md:px-8 lg:py-24">
        <p class="text-2xl leading-relaxed font-bold text-balance">
            {{ __('Sorry, we can’t find that page.', 'sage') }}
        </p>

        <p class="mt-4 leading-relaxed">
            {{ __('It may have moved, or the link that brought you here may be out of date. Try a search, or head back to the homepage.', 'sage') }}
        </p>

        <div class="mt-8">
            {!! get_search_form(false) !!}
        </div>

        <div class="wp-block-buttons mt-10">
            <div class="wp-block-button">
                <a class="wp-block-button__link wp-element-button" href="{{ home_url('/') }}">
                    {{ __('Back to the homepage', 'sage') }}
                </a>
            </div>
        </div>
    </div>
@endsection
