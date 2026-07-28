@extends('layouts.app')

@section('content')
    @while (have_posts())
        @php(the_post())
        @include('partials.page-header')

        <div class="page-content prose py-16 lg:py-24">
            @php(the_content())
        </div>
    @endwhile
@endsection
