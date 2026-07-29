@extends('layouts.app')

@php
    // Two independent, DB-sorted lists rather than the main-query loop:
    // upcoming events soonest-first, past events most-recent-first. Both are
    // rendered server-side; Alpine just toggles which one is visible.
    $now = current_time('Y-m-d H:i:s');

    $eventQuery = fn(string $compare, string $order) => new \WP_Query([
        'post_type' => 'event',
        'posts_per_page' => -1,
        'meta_key' => 'start',
        'meta_type' => 'DATETIME',
        'orderby' => 'meta_value',
        'order' => $order,
        'meta_query' => [
            [
                'key' => 'start',
                'value' => $now,
                'compare' => $compare,
                'type' => 'DATETIME',
            ],
        ],
    ]);

    $upcoming = $eventQuery('>=', 'ASC');
    $past = $eventQuery('<', 'DESC');
@endphp

@section('content')
    @include('partials.page-header')

    <div
        class="max-w-wide mx-auto px-4 py-16 md:px-8 lg:py-24"
        x-data="{ tab: window.location.hash === '#past' ? 'past' : 'upcoming' }"
        x-init="
            $watch('tab', (v) =>
                history.replaceState(null, '', v === 'past' ? '#past' : location.pathname + location.search),
            )
        "
    >
        <div class="mb-12 flex flex-wrap gap-3" role="group" aria-label="{{ __('Filter events', 'sage') }}">
            <button
                type="button"
                @click="tab = 'upcoming'"
                :aria-pressed="tab === 'upcoming'"
                :class="tab === 'upcoming' ? 'bg-black text-white' : 'text-black hover:bg-black/5'"
                class="rounded-full border border-black/15 px-6 py-2 text-sm font-bold transition duration-300"
            >
                {{ __('Upcoming', 'sage') }}
                <span class="opacity-60">({{ $upcoming->found_posts }})</span>
            </button>

            <button
                type="button"
                @click="tab = 'past'"
                :aria-pressed="tab === 'past'"
                :class="tab === 'past' ? 'bg-black text-white' : 'text-black hover:bg-black/5'"
                class="rounded-full border border-black/15 px-6 py-2 text-sm font-bold transition duration-300"
            >
                {{ __('Past', 'sage') }}
                <span class="opacity-60">({{ $past->found_posts }})</span>
            </button>
        </div>

        <div role="region" aria-label="{{ __('Upcoming events', 'sage') }}" x-show="tab === 'upcoming'">
            @if ($upcoming->have_posts())
                <div class="grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
                    @while ($upcoming->have_posts())
                        @php($upcoming->the_post())
                        @include('partials.content-event')
                    @endwhile
                    @php(wp_reset_postdata())
                </div>
            @else
                <p class="text-gray text-lg">
                    {{ __('There are no upcoming events right now — check back soon.', 'sage') }}
                </p>
            @endif
        </div>

        <div role="region" aria-label="{{ __('Past events', 'sage') }}" x-show="tab === 'past'" x-cloak>
            @if ($past->have_posts())
                <div class="grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
                    @while ($past->have_posts())
                        @php($past->the_post())
                        @include('partials.content-event')
                    @endwhile
                    @php(wp_reset_postdata())
                </div>
            @else
                <p class="text-gray text-lg">{{ __('There are no past events to show yet.', 'sage') }}</p>
            @endif
        </div>
    </div>
@endsection
