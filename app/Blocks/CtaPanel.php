<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class CtaPanel extends Block
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
    public $name = 'CTA panel';

    /**
     * The block slug — set explicitly because "CTA" would otherwise
     * kebab-case into "c-t-a-panel" (and break the view lookup).
     *
     * @var string
     */
    public $slug = 'cta-panel';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'Rounded call-to-action panel with an image or illustration beside the message.';

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
    public $keywords = ['cta', 'call to action', 'panel', 'member', 'donate', 'banner'];

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
    public $align = 'wide';

    /**
     * The block styles. Dark: black panel, illustration floating beside
     * the message. Light: white panel with a hand-drawn border and a
     * photo filling one side.
     *
     * @var array
     */
    public $styles = ['dark', 'light'];

    /**
     * The supported block features.
     *
     * @var array
     */
    public $supports = [
        'align' => ['wide', 'full'],
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
            'placeholder' => 'Help shape the East Marsh. Become a member.',
        ],
        'core/paragraph' => [
            'placeholder' => 'Why should someone act? Keep it to a sentence or two.',
        ],
        'core/buttons' => [],
    ];

    /**
     * Data to be passed to the block before rendering.
     */
    public function with(): array
    {
        return [
            'image' => get_field('image') ?: null,
            'image_side' => get_field('image_side') ?: 'left',
        ];
    }

    /**
     * The block field group.
     */
    public function fields(): array
    {
        $fields = Builder::make('cta_panel');

        $fields
            ->addImage('image', [
                'label' => 'Image',
                'instructions' => 'An illustration (dark style) or photo (light style).',
                'return_format' => 'id',
                'preview_size' => 'medium',
            ])
            ->addButtonGroup('image_side', [
                'label' => 'Image side',
                'choices' => [
                    'left' => 'Left',
                    'right' => 'Right',
                ],
                'default_value' => 'left',
            ]);

        return $fields->build();
    }
}
