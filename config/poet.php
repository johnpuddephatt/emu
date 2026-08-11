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

        'person' => [
            'enter_title_here' => 'Enter person’s name',
            'menu_icon' => 'dashicons-groups',
            'supports' => ['title', 'editor', 'revisions', 'page-attributes', 'thumbnail'],

            /*
             * Bios are surfaced by the People block (in a modal), so there are no
             * single-person pages — without a template of their own they'd render
             * as bare, headerless fallbacks. Flip `public` back on and add
             * `single-person.blade.php` if profile pages are wanted later.
             */
            'public' => false,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'has_archive' => false,
            'rewrite' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_rest' => true,
            'admin_cols' => [
                'featured_image' => [
                    'title' => 'Photo',
                    'featured_image' => 'thumbnail',
                ],
                'role_title' => [
                    'title' => 'Role',
                    'meta_key' => 'role_title',
                ],
                'role_type' => [
                    'taxonomy' => 'role_type',
                ],
            ],
            'admin_filters' => [
                'role_type' => [
                    'taxonomy' => 'role_type',
                ],
            ],
            'labels' => [
                'singular' => 'Person',
                'plural' => 'People',
            ],
        ],

        'workstream' => [
            'enter_title_here' => 'Enter workstream name',
            'menu_icon' => 'dashicons-networking',
            // No excerpt — the "Strapline" field on Workstream details replaces it.
            'supports' => ['title', 'editor', 'revisions', 'page-attributes', 'thumbnail'],
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

    /*
    |--------------------------------------------------------------------------
    | Taxonomies
    |--------------------------------------------------------------------------
    |
    | Taxonomies registered by Poet using the Extended CPTs library.
    |
    */

    'taxonomy' => [

        'role_type' => [
            'links' => ['person'],
            'meta_box' => 'simple',
            'labels' => [
                'singular' => 'Role type',
                'plural' => 'Role types',
            ],
        ],

    ],

];
