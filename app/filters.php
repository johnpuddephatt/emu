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
 * Drop paragraph blocks the editor left empty.
 *
 * They add a phantom line of space wherever they land, and a trailing one
 * sits between a full-bleed block and the end of the content wrapper —
 * which stops `.page-content` collapsing its padding there (see
 * `_content.css`). A paragraph holding only a non-breaking space is left
 * alone: that one was typed on purpose.
 *
 * @param  string  $html
 * @return string
 */
add_filter('render_block_core/paragraph', function ($html) {
    if (preg_match('/<(img|br|iframe|svg|video|audio)\b/i', $html)) {
        return $html;
    }

    return trim(wp_strip_all_tags($html)) === '' ? '' : $html;
});


/**
 * Let pages live underneath /workstreams/.
 *
 * Extended CPTs gives the `workstream` post type the rewrite slug
 * `workstreams`, and its rule sits at position 56 of the rewrite table
 * while the first `pagename` rule is at 136. WordPress stops at the first
 * regex that matches, so /workstreams/anything is always queried as a
 * workstream — a child page of the Workstreams page 404s and no amount of
 * flushing helps, because the order is regenerated the same way.
 *
 * Rather than move the CPT off the slug (which would break the five live
 * workstream URLs), fall back to a page when the workstream doesn't exist.
 * A real workstream always wins, so existing URLs are untouched.
 *
 * @param  array  $vars
 * @return array
 */
add_filter('request', function ($vars) {
    if (empty($vars['workstream'])) {
        return $vars;
    }

    $existing = get_posts([
        'post_type' => 'workstream',
        'name' => $vars['workstream'],
        'post_status' => 'any',
        'numberposts' => 1,
        'fields' => 'ids',
        'no_found_rows' => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    ]);

    if ($existing) {
        return $vars;
    }

    // `.+?` in the rewrite rule swallows slashes too, so this covers
    // grandchildren as well as direct children.
    $path = 'workstreams/' . $vars['workstream'];

    if (! get_page_by_path($path)) {
        return $vars;
    }

    // WordPress normalises the CPT's query var into `post_type` + `name`
    // as well, so all three have to go or the query still looks for a
    // workstream. `page` is left alone — the page rule sets it too.
    unset($vars['workstream'], $vars['post_type'], $vars['name']);

    $vars['pagename'] = $path;

    return $vars;
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
