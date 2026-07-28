@extends('layouts.app')

@section('content')
    @while (have_posts())
        @php(the_post())
        {{--
      Pages with a hero block are self-contained landing pages (home,
      workstreams); everything else gets the generated page header and
      "In this section" jump links.
    --}}
        @if (has_block('acf/hero'))
            <div class="page-content">
                {!! $content !!}
            </div>
        @else
            @include('partials.page-header')

            @if ($toc)
                <div class="relative z-20 mx-auto mt-8 max-w-wide px-4 md:px-8">
                    <x-toc :items="$toc" />
                </div>
            @endif

            <div class="page-content prose py-16 lg:py-24">
                {!! $content !!}
            </div>
        @endif
    @endwhile
@endsection
