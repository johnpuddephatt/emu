<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Event extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'single-event',
    ];

    /**
     * Data to be passed to the view.
     */
    public function with(): array
    {
        $id = get_post()->ID ?? null;

        $start = ($value = get_field('start', $id)) ? strtotime($value) : null;
        $end = ($value = get_field('end', $id)) ? strtotime($value) : null;

        $when = null;

        if ($start) {
            $when = wp_date('l j F Y, g:ia', $start);

            if ($end) {
                $when .= wp_date('Y-m-d', $end) === wp_date('Y-m-d', $start)
                    ? ' – ' . wp_date('g:ia', $end)
                    : ' – ' . wp_date('l j F Y, g:ia', $end);
            }
        }

        return [
            'start' => $start,
            'when' => $when,
            'location' => get_field('location', $id),
        ];
    }
}
