@extends('layouts.app')

@section('content')
  @include('partials.page-header')

  <div class="mx-auto max-w-wide px-4 py-16 md:px-8 lg:py-24">
    @if (! have_posts())
      <x-alert type="warning">
        {!! __('Sorry, no results were found.', 'sage') !!}
      </x-alert>

      {!! get_search_form(false) !!}
    @endif

    <div class="grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
      @while(have_posts()) @php(the_post())
        @includeFirst(['partials.content-' . get_post_type(), 'partials.content'])
      @endwhile
    </div>

    {!! get_the_posts_pagination([
        'mid_size' => 2,
        'prev_text' => __('&larr; Newer', 'sage'),
        'next_text' => __('Older &rarr;', 'sage'),
        'class' => 'mt-16 [&_.nav-links]:flex [&_.nav-links]:flex-wrap [&_.nav-links]:justify-center [&_.nav-links]:gap-4 [&_.page-numbers]:font-bold [&_.page-numbers.current]:text-gray [&_a]:no-underline [&_a:hover]:underline',
    ]) !!}
  </div>
@endsection
