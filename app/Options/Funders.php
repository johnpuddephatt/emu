<?php

namespace App\Options;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Options as Field;

class Funders extends Field
{
    /**
     * The option page menu name.
     *
     * @var string
     */
    public $name = 'Funders';

    /**
     * The option page document title.
     *
     * @var string
     */
    public $title = 'Funders and supporters';

    /**
     * The slug of another admin page to be used as a parent.
     *
     * Sits under Appearance, alongside Menus and Social media.
     *
     * @var string|null
     */
    public $parent = 'themes.php';

    /**
     * The option page field group.
     */
    public function fields(): array
    {
        $fields = Builder::make('funders');

        $fields
            ->addText('funders_heading', [
                'label' => 'Heading',
                'instructions' => 'Shown above the logos. Leave empty to hide it.',
                'default_value' => 'With support from',
                'maxlength' => 80,
            ])
            ->addRepeater('funders', [
                'label' => 'Logos',
                'instructions' => 'Shown on every page, just above the footer. Drag the handles to reorder them. Logos are sized to look about the same size as each other whatever shape they are, so upload them at their natural proportions — no need to pad them out to a square. Transparent PNGs or SVGs work best.',
                'layout' => 'table',
                'button_label' => 'Add funder',
            ])
            ->addText('name', [
                'label' => 'Name',
                'instructions' => 'Used as the logo’s alt text, so write it as you would say it out loud.',
                'required' => 1,
                'wrapper' => ['width' => '35'],
            ])
            ->addImage('logo', [
                'label' => 'Logo',
                'instructions' => 'Cropped as uploaded — trim any surrounding whitespace first.',
                'required' => 1,
                'return_format' => 'id',
                'preview_size' => 'medium',
                'library' => 'all',
                'mime_types' => 'jpg,jpeg,png,gif,svg,webp',
                'wrapper' => ['width' => '30'],
            ])
            ->addUrl('url', [
                'label' => 'Link',
                'instructions' => 'Optional. The full address, including https://',
                'wrapper' => ['width' => '35'],
            ])
            ->endRepeater();

        return $fields->build();
    }
}
