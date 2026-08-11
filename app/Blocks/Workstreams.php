<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;

class Workstreams extends Block
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
    public $name = 'Workstreams';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'Dark section listing the workstreams as illustrated cards inside a hand-drawn frame.';

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
    public $icon = 'grid-view';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['cards', 'areas', 'focus', 'what we do', 'strip'];

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
     * The block styles. "Cards" is the homepage strip (dark section,
     * compact cards in a grid); "Rows" is the index-page layout
     * (light section, one full-width card per workstream with the
     * illustration alternating sides).
     *
     * @var array
     */
    public $styles = ['cards', 'rows'];

    /**
     * The block template.
     *
     * @var array
     */
    public $template = [
        'core/heading' => [
            'level' => 2,
            'placeholder' => 'What we\'re doing',
            'textAlign' => 'center',
        ],
        'core/paragraph' => [
            'placeholder' => 'East Marsh United is creating change across five areas:',
            'align' => 'center',
        ],
    ];

    /**
     * The block preview example data.
     *
     * @var array
     */
    public $example = [
        'cards' => [
            ['illustration' => null, 'title' => 'Homes & Housing', 'text' => 'Safe, affordable forever homes with community at the heart.', 'link' => ['url' => '#', 'title' => 'Read more']],
            ['illustration' => null, 'title' => 'Community', 'text' => 'Neighbours looking out for each other, sharing meals, stories and mischief.', 'link' => ['url' => '#', 'title' => 'Read more']],
            ['illustration' => null, 'title' => 'People & Place', 'text' => 'Standing up for fairness, respect and quiet pride.', 'link' => ['url' => '#', 'title' => 'Read more']],
        ],
    ];

    /**
     * Data to be passed to the block before rendering.
     */
    public function with(): array
    {
        $cards = collect(get_posts([
            'post_type' => 'workstream',
            'numberposts' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC',
        ]))->map(fn($workstream) => [
            'illustration' => get_post_thumbnail_id($workstream) ?: null,
            'title' => get_the_title($workstream),
            'text' => get_field('strapline', $workstream->ID) ?: '',
            // Only the "Rows" layout uses this, falling back to the strapline.
            'short_description' => get_field('short_description', $workstream->ID) ?: '',
            'link' => [
                'url' => get_permalink($workstream),
                'title' => 'Read more',
                'target' => '',
            ],
        ])->all();

        return [
            'cards' => $cards ?: ($this->preview ? $this->example['cards'] : []),
        ];
    }
}
