<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class Testimonial extends Block
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
    public $name = 'Testimonial';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A customer quote with attribution.';

    /**
     * The block category.
     *
     * @var string
     */
    public $category = 'text';

    /**
     * The block icon.
     *
     * @var string|array
     */
    public $icon = 'format-quote';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['quote', 'review', 'testimonial'];

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
    public $align = '';

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
        'jsx' => false,
    ];

    /**
     * The block preview example data.
     *
     * @var array
     */
    public $example = [
        'quote' => 'This product completely changed how we work. I can’t imagine going back.',
        'author' => 'Jane Smith',
        'role' => 'Head of Product, Acme Co.',
        'avatar' => null,
    ];

    /**
     * Data to be passed to the block before rendering.
     */
    public function with(): array
    {
        return [
            'quote' => $this->quote(),
            'author' => get_field('author') ?: $this->example['author'],
            'role' => get_field('role') ?: $this->example['role'],
            'avatar' => get_field('avatar'),
        ];
    }

    /**
     * The block field group.
     */
    public function fields(): array
    {
        $fields = Builder::make('testimonial');

        $fields
            ->addTextarea('quote', [
                'label' => 'Quote',
                'rows' => 3,
                'required' => true,
            ])
            ->addText('author', [
                'label' => 'Author',
            ])
            ->addText('role', [
                'label' => 'Role / Company',
            ])
            ->addImage('avatar', [
                'label' => 'Avatar',
                'return_format' => 'id',
                'preview_size' => 'thumbnail',
            ]);

        return $fields->build();
    }

    /**
     * Retrieve the quote.
     *
     * @return string
     */
    public function quote()
    {
        return get_field('quote') ?: $this->example['quote'];
    }
}
