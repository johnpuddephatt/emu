<?php

namespace App\Blocks;

use Illuminate\Support\Str;
use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class Icon extends Block
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
    public $name = 'Icon';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A decorative icon or doodle from the theme icon set — star, loop, lines and friends.';

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
    public $icon = 'star-empty';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['icon', 'doodle', 'star', 'loop', 'decoration', 'svg'];

    /**
     * The default block mode.
     *
     * @var string
     */
    public $mode = 'preview';

    /**
     * The supported block features.
     *
     * @var array
     */
    public $supports = [
        'align' => false,
        'anchor' => true,
        'mode' => true,
        'multiple' => true,
        'jsx' => false,
    ];

    /**
     * The block preview example data.
     *
     * @var array
     */
    public $example = [
        'icon' => 'star',
        'size' => 'md',
        'align' => 'left',
        'rotate' => 6,
        'color' => '',
    ];

    /**
     * Data to be passed to the block before rendering.
     */
    public function with(): array
    {
        $icon = get_field('icon') ?: ($this->preview ? $this->example['icon'] : null);

        // Guard against icons removed from the theme after insertion.
        if ($icon && ! file_exists(get_theme_file_path("resources/icons/{$icon}.svg"))) {
            $icon = null;
        }

        return [
            'icon' => $icon,
            'size' => get_field('size') ?: 'md',
            'align' => get_field('align') ?: 'left',
            'rotate' => (int) get_field('rotate'),
            'color' => get_field('color') ?: '',
        ];
    }

    /**
     * The block field group.
     */
    public function fields(): array
    {
        $fields = Builder::make('icon');

        $icons = collect(glob(get_theme_file_path('resources/icons/*.svg')))
            ->mapWithKeys(function ($file) {
                $name = basename($file, '.svg');

                return [$name => Str::headline($name)];
            })
            ->all();

        $fields
            ->addSelect('icon', [
                'choices' => $icons,
                'default_value' => 'star',
                'required' => true,
                'ui' => true,
            ])
            ->addButtonGroup('size', [
                'choices' => ['sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large'],
                'default_value' => 'md',
            ])
            ->addButtonGroup('align', [
                'choices' => ['left' => 'Left', 'center' => 'Center', 'right' => 'Right'],
                'default_value' => 'left',
            ])
            ->addRange('rotate', [
                'label' => 'Rotation',
                'min' => -30,
                'max' => 30,
                'step' => 2,
                'default_value' => 0,
                'append' => '°',
            ])
            ->addSelect('color', [
                'label' => 'Colour',
                'instructions' => 'Leave empty to keep the icon\'s own colours.',
                'choices' => [
                    'black' => 'Black',
                    'yellow' => 'Yellow',
                    'pink' => 'Pink',
                    'green' => 'Green',
                    'blue' => 'Blue',
                    'red' => 'Red',
                ],
                'allow_null' => true,
            ]);

        return $fields->build();
    }
}
