<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class Hero extends Block
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
    public $name = 'Hero';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'Homepage hero with photo collage, logo and welcome message.';

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
    public $icon = 'cover-image';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['hero', 'welcome', 'header', 'collage'];

    /**
     * Restrict to one hero per page.
     *
     * @var bool
     */
    public $multiple = false;

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
        'multiple' => false,
        'jsx' => true,
    ];

    /**
     * The block styles. "Centered" is the homepage hero (logo + centred
     * text, photos floating either side); "Split" is the page-header
     * variant (left-aligned text, photo collage on the right).
     *
     * @var array
     */
    public $styles = ['centered', 'split'];

    /**
     * The block template.
     *
     * @var array
     */
    public $template = [
        'core/heading' => [
            'level' => 1,
            'placeholder' => 'Welcome to our corner of the world!',
            'textAlign' => 'center',
        ],
        'core/paragraph' => [
            'placeholder' => 'Introduce East Marsh United…',
            'align' => 'center',
        ],
        'core/buttons' => [],
    ];

    /**
     * Data to be passed to the block before rendering.
     */
    public function with(): array
    {
        return [
            'images' => get_field('photos') ?: [],
        ];
    }

    /**
     * The block field group.
     */
    public function fields(): array
    {
        $fields = Builder::make('hero');

        $fields
            ->addGallery('photos', [
                'label' => 'Collage photos',
                'instructions' => 'Four to six photos, scattered around the welcome message in the Centered layout or collaged beside it in the Split layout. They parallax as the page scrolls. On small screens the third and fifth photos are dropped.',
                'return_format' => 'id',
                'min' => 4,
                'max' => 6,
                'preview_size' => 'medium',
            ]);

        return $fields->build();
    }
}
