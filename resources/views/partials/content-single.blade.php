<article @php(post_class('h-entry'))>
    <header>
        <h1 class="p-name">
            {!! $title !!}
        </h1>

        @include('partials.entry-meta')
    </header>

    <div class="prose page-content">
        @php(the_content())
    </div>


</article>
