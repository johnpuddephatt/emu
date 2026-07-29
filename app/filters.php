<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    // return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
    return '&hellip;';
});

add_filter('excerpt_length', function () {
    return 30;
});


/**
 * Register a minimal toolbar for ACF wysiwyg fields — just bold,
 * italic, link and bullets. Use with 'toolbar' => 'minimal'.
 *
 * @return array
 */
add_filter('acf/fields/wysiwyg/toolbars', function ($toolbars) {
    $toolbars['Minimal'] = [
        1 => ['bold', 'italic', 'link', 'bullist'],
    ];
    return $toolbars;
});

/**
 * The page backing the events archive header (Events → Archive page) only
 * exists to supply a title/image/excerpt. Redirect its own URL to the real
 * archive at /events/ so it isn't a duplicate, crawlable page.
 */
add_action('template_redirect', function () {
    if (! is_page()) {
        return;
    }

    $backing = (int) get_field('events_archive_page', 'option');

    if ($backing && $backing === get_queried_object_id()) {
        wp_safe_redirect(get_post_type_archive_link('event'), 301);
        exit;
    }
});
