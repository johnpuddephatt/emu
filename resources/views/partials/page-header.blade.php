@php
    // On archives/search the standfirst and illustration come from a
    // backing page — never from the first post in the loop. For the blog
    // that's WordPress's built-in "posts page"; for the events archive
    // it's the page picked in Events → Archive page. $title comes from the
    // Post composer, which knows about archive/search/home titles.
    $headerPost = match (true) {
        is_home() => get_option('page_for_posts'),
        is_post_type_archive('event') => get_field('events_archive_page', 'option'),
        is_singular() => get_the_ID(),
        default => null,
    };
    $thumbnail = $headerPost ? get_post_thumbnail_id($headerPost) : null;
    $excerpt = $headerPost && has_excerpt($headerPost) ? get_the_excerpt($headerPost) : null;
@endphp

<header class="relative overflow-x-clip has-black-background-color">
    <div aria-hidden="true" class="pointer-events-none absolute -bottom-10 right-0 w-[32rem] max-w-none  lg:w-[60rem] ">
        @svg('page-lines', 'absolute bottom-10 h-auto w-full text-pink [&_path]:stroke-[9]')

    </div>

    <div class="relative mx-auto grid max-w-wide gap-x-12 px-4 pt-32 md:px-8 lg:grid-cols-2 lg:pt-36">
        <div class="relative z-20 self-center pb-12 lg:pb-20">
            <h1 class="text-4xl font-bold text-balance lg:text-5xl">{!! $title ?? get_the_title() !!}</h1>

            @if ($excerpt)
                <p class="mt-6 max-w-md font-bold leading-relaxed">{{ $excerpt }}</p>
            @endif
        </div>

        @if ($thumbnail)
            <div class="relative mx-auto mt-6 w-fit self-end lg:mt-0">
                {{-- Swoosh lines weaving behind the illustration, spilling past the section edge --}}

                {!! wp_get_attachment_image($thumbnail, 'large', false, [
                    'class' => 'relative z-10 h-auto w-auto max-h-96 lg:max-h-[30rem]',
                    'sizes' => '(min-width: 1024px) 30vw, 60vw',
                ]) !!}
            </div>
        @endif
    </div>

    <a href="{{ home_url('/') }}" class="absolute top-4 left-4 z-30 lg:top-6 lg:left-6" aria-label="{{ $siteName }}">
        @svg('logo', 'h-auto w-24 fill-white lg:w-28')
    </a>
</header>
