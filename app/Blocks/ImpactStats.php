<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class ImpactStats extends Block
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
    public $name = 'Impact stats';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'Carousel of photos with a headline stat under each one.';

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
    public $icon = 'images-alt2';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['stats', 'impact', 'carousel', 'difference', 'numbers'];

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
            'placeholder' => 'We make a difference',
        ],
    ];

    /**
     * The block preview example data.
     *
     * @var array
     */
    public $example = [
        'slides' => [
            ['image' => null, 'stat' => '18 homes', 'caption' => 'created – and counting'],
            ['image' => null, 'stat' => '100 years', 'caption' => 'of housing security is our goal'],
            ['image' => null, 'stat' => '300+ residents', 'caption' => 'shaping the Community Plan'],
            ['image' => null, 'stat' => '£500,000 raised', 'caption' => 'through our community share offer'],
        ],
    ];

    /**
     * Data to be passed to the block before rendering.
     */
    public function with(): array
    {
        return [
            'slides' => get_field('slides') ?: ($this->preview ? $this->example['slides'] : []),
        ];
    }

    /**
     * The block field group.
     */
    public function fields(): array
    {
        $fields = Builder::make('impact_stats');

        $fields
            ->addRepeater('slides', [
                'label' => 'Slides',
                'layout' => 'block',
                'button_label' => 'Add slide',
                'min' => 1,
            ])
                ->addImage('image', [
                    'return_format' => 'id',
                    'preview_size' => 'medium',
                ])
                ->addText('stat', [
                    'label' => 'Stat',
                    'instructions' => 'The headline figure, e.g. "18 homes"',
                ])
                ->addText('caption', [
                    'label' => 'Caption',
                    'instructions' => 'Supporting line, e.g. "created – and counting"',
                ])
            ->endRepeater();

        return $fields->build();
    }
}
