<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class UpcomingEvents extends Block
{
    /**
     * The ACF block version.
     *
     * @var int
     */
    public $blockVersion = 3;

    /**
     * The WordPress block API version.
     *
     * @var int
     */
    public $apiVersion = 3;

    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Upcoming events';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'Cream section listing the next few events, with an illustration alongside.';

    /**
     * The block category.
     *
     * @var string
     */
    public $category = 'design';

    /**
     * The block icon.
     *
     * @var string|array
     */
    public $icon = 'calendar-alt';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['events', 'upcoming', 'calendar', 'meetings'];

    /**
     * The default block mode.
     *
     * @var string
     */
    public $mode = 'preview';

    /**
     * The default block alignment.
     *
     * @var string
     */
    public $align = 'full';

    /**
     * The supported block features.
     *
     * @var array
     */
    public $supports = [
        'align' => ['full'],
        'anchor' => true,
        'mode' => true,
        'multiple' => true,
        'jsx' => true,
    ];

    /**
     * The block template.
     *
     * @var array
     */
    public $template = [
        'core/heading' => [
            'level' => 2,
            'placeholder' => 'Events coming up',
        ],
    ];

    /**
     * Data to be passed to the block before rendering.
     */
    public function with(): array
    {
        $events = get_posts([
            'post_type' => 'event',
            'posts_per_page' => get_field('number') ?: 3,
            'meta_key' => 'start',
            'meta_type' => 'DATETIME',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'meta_query' => [
                [
                    'key' => 'start',
                    'value' => current_time('Y-m-d H:i:s'),
                    'compare' => '>=',
                    'type' => 'DATETIME',
                ],
            ],
        ]);

        return [
            'events' => $events,
            'illustration' => get_field('illustration') ?: null,
            'all_events_link' => get_field('all_events_link') ?: null,
        ];
    }

    /**
     * The block field group.
     */
    public function fields(): array
    {
        $fields = Builder::make('upcoming_events');

        $fields
            ->addImage('illustration', [
                'label' => 'Illustration',
                'return_format' => 'id',
                'preview_size' => 'medium',
            ])
            ->addNumber('number', [
                'label' => 'Number of events',
                'default_value' => 3,
                'min' => 1,
                'max' => 6,
            ])
            ->addLink('all_events_link', [
                'label' => '"See all events" link',
            ]);

        return $fields->build();
    }
}
