<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class LatestNews extends Block
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
    public $name = 'Latest news';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'The most recent post featured large, with a grid of further posts below.';

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
    public $icon = 'megaphone';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['news', 'posts', 'latest', 'blog'];

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
            'placeholder' => 'Latest news from the East Marsh',
        ],
    ];

    /**
     * Data to be passed to the block before rendering.
     */
    public function with(): array
    {
        $posts = get_posts([
            'posts_per_page' => get_field('number') ?: 4,
            'post_type' => 'post',
            'orderby' => 'date',
            'order' => 'DESC',
        ]);

        return [
            'featured' => $posts[0] ?? null,
            'posts' => array_slice($posts, 1),
        ];
    }

    /**
     * The block field group.
     */
    public function fields(): array
    {
        $fields = Builder::make('latest_news');

        $fields
            ->addNumber('number', [
                'label' => 'Number of posts',
                'instructions' => 'Includes the featured post.',
                'default_value' => 4,
                'min' => 1,
                'max' => 7,
            ]);

        return $fields->build();
    }
}
