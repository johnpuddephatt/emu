<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class PageSignpost extends Block
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
    public $name = 'Page signpost';

    /**
     * The block slug.
     *
     * @var string
     */
    public $slug = 'page-signpost';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'Signpost one or more pages elsewhere on the site, each as a card with a "read more" button.';

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
    public $icon = 'admin-links';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['signpost', 'link', 'pages', 'related', 'cards'];

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
            'placeholder' => 'Find out more',
        ],
    ];

    /**
     * The block preview example data.
     *
     * @var array
     */
    public $example = [
        'cards' => [
            ['title' => 'Homes & Housing', 'text' => 'Safe, affordable forever homes with community at the heart.', 'image' => null, 'url' => '#', 'label' => 'Read more'],
            ['title' => 'Community', 'text' => 'Neighbours looking out for each other, sharing meals, stories and support.', 'image' => null, 'url' => '#', 'label' => 'Read more'],
            ['title' => 'Creativity', 'text' => 'Choirs, clubs, festivals and projects that spark imagination and joy.', 'image' => null, 'url' => '#', 'label' => 'Read more'],
        ],
    ];

    /**
     * Data to be passed to the block before rendering.
     */
    public function with(): array
    {
        $cards = $this->cards();

        return [
            'cards' => $cards,
            // Two cards look lost in a three-column grid, so the grid only
            // grows as wide as there are cards to fill it.
            'columns' => min(count($cards) ?: 1, 3),
        ];
    }

    /**
     * The signposted pages, with any per-card overrides applied.
     */
    public function cards(): array
    {
        $label = get_field('link_label') ?: __('Read more', 'sage');

        $cards = collect(get_field('pages') ?: [])
            ->filter(fn($row) => ! empty($row['page']))
            ->map(function ($row) use ($label) {
                $id = (int) $row['page'];

                return [
                    // Titles are stored with entities ("Homes &amp; Housing"),
                    // and the view escapes on output — decode so it isn't done twice.
                    'title' => $row['title'] ?: html_entity_decode(get_the_title($id), ENT_QUOTES, 'UTF-8'),
                    'text' => $row['text'] ?: $this->summary($id),
                    'image' => $row['image'] ?: (get_post_thumbnail_id($id) ?: null),
                    'url' => get_permalink($id),
                    'label' => $row['link_label'] ?: $label,
                ];
            })
            ->all();

        return $cards ?: ($this->preview ? $this->example['cards'] : []);
    }

    /**
     * The standfirst for a signposted page — workstreams keep theirs in a
     * Strapline field rather than an excerpt.
     */
    protected function summary(int $id): string
    {
        if ($strapline = get_field('strapline', $id)) {
            return $strapline;
        }

        return has_excerpt($id) ? get_the_excerpt($id) : '';
    }

    /**
     * The block field group.
     */
    public function fields(): array
    {
        $fields = Builder::make('page_signpost');

        $fields
            ->addRepeater('pages', [
                'label' => 'Pages',
                'layout' => 'block',
                'button_label' => 'Add page',
                'min' => 1,
            ])
                ->addPostObject('page', [
                    'label' => 'Page',
                    'post_type' => ['page', 'workstream', 'post', 'event'],
                    'return_format' => 'id',
                    'required' => true,
                ])
                ->addText('title', [
                    'label' => 'Title override',
                    'instructions' => 'Leave empty to use the page’s own title.',
                ])
                ->addTextarea('text', [
                    'label' => 'Description override',
                    'instructions' => 'Leave empty to use the page’s strapline or excerpt.',
                    'rows' => 3,
                    'new_lines' => '',
                ])
                ->addImage('image', [
                    'label' => 'Image override',
                    'instructions' => 'Leave empty to use the page’s featured image.',
                    'return_format' => 'id',
                    'preview_size' => 'medium',
                ])
                ->addText('link_label', [
                    'label' => 'Button label override',
                ])
            ->endRepeater()
            ->addText('link_label', [
                'label' => 'Button label',
                'instructions' => 'Used for every card unless overridden.',
                'placeholder' => 'Read more',
            ]);

        return $fields->build();
    }
}
