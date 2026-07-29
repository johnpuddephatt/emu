@extends('layouts.app')

@section('content')
    @while (have_posts())
        @php(the_post())
        @include('partials.page-header')

        @if ($when || $location)
            <div class="max-w-wide relative z-20 mx-auto mt-8 px-4 md:px-8">
                <div class="has-black-background-color flex flex-col gap-x-16 gap-y-4 rounded-xl px-6 py-6 sm:px-10 lg:flex-row lg:py-8">
                    @if ($when)
                        <p class="m-0">
                            <span class="text-yellow block text-sm font-bold">{{ __('When', 'sage') }}</span>
                            <time class="font-bold" datetime="{{ wp_date('c', $start) }}">{{ $when }}</time>
                        </p>
                    @endif

                    @if ($location)
                        <p class="m-0">
                            <span class="text-yellow block text-sm font-bold">{{ __('Where', 'sage') }}</span>
                            <span class="font-bold">{{ $location }}</span>
                        </p>
                    @endif
                </div>
            </div>
        @endif

        <div class="page-content prose py-16 lg:py-24">
            @php(the_content())
        </div>
    @endwhile
@endsection
