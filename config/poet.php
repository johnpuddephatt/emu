<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Post Types
    |--------------------------------------------------------------------------
    |
    | Post types registered by Poet using the Extended CPTs library.
    | <https://github.com/johnbillion/extended-cpts>
    |
    */

    'post' => [

        'event' => [
            'enter_title_here' => 'Enter event name',
            'menu_icon' => 'dashicons-calendar-alt',
            'supports' => ['title', 'editor', 'excerpt', 'revisions', 'thumbnail'],
            'show_in_rest' => true,
            'has_archive' => true,
            'admin_cols' => [
                'event_date' => [
                    'title' => 'Event date',
                    'meta_key' => 'start',
                ],
            ],
            'labels' => [
                'singular' => 'Event',
                'plural' => 'Events',
            ],
        ],

        'workstream' => [
            'enter_title_here' => 'Enter workstream name',
            'menu_icon' => 'dashicons-networking',
            'supports' => ['title', 'editor', 'excerpt', 'revisions', 'page-attributes', 'thumbnail'],
            'show_in_rest' => true,
            'has_archive' => false,
            'admin_cols' => [
                'featured_image' => [
                    'title' => 'Illustration',
                    'featured_image' => 'thumbnail',
                ],
            ],
            'labels' => [
                'singular' => 'Workstream',
                'plural' => 'Workstreams',
            ],
        ],

    ],

];
