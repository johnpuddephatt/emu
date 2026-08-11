<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class Timeline extends Block
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
    public $name = 'Timeline';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A vertical timeline of highlights — images, cards, stats, text and quotes scattered along a central line.';

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
    public $icon = 'ellipsis';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['timeline', 'history', 'highlights', 'journey', 'milestones'];

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
        'jsx' => false,
    ];

    /**
     * The block preview example data.
     *
     * @var array
     */
    public $example = [
        'intro' => "Take a look at what we've been up to",
        'items' => [
            ['acf_fc_layout' => 'image', 'image' => null],
            ['acf_fc_layout' => 'card', 'image' => null, 'title' => 'Wild Pixels', 'text' => 'Working with artists and school pupils, Wild Pixels imagined what a greener East Marsh might look and sound like.'],
            ['acf_fc_layout' => 'stat', 'number' => '48%', 'text' => 'of our community have got involved in a creative project over the past two years.'],
            ['acf_fc_layout' => 'text', 'text' => '<strong>Local creatives</strong> work with us as equals, shaping projects from the ground up.'],
        ],
        'whats_next' => [
            'title' => "What's next?",
            'text' => 'There is plenty more to come.',
            'link' => null,
        ],
    ];

    /**
     * Data to be passed to the block before rendering.
     */
    public function with(): array
    {
        return [
            'intro' => get_field('intro') ?: ($this->preview ? $this->example['intro'] : ''),
            'items' => get_field('items') ?: ($this->preview ? $this->example['items'] : []),
            'whatsNext' => $this->whatsNext(),
        ];
    }

    /**
     * The "What's next" panel, always rendered at the end of the timeline.
     * Returns null unless the editor has filled something in — the example
     * only stands in for the empty inserter preview, so a deliberately
     * blank panel still previews as blank while editing.
     */
    protected function whatsNext(): ?array
    {
        $whatsNext = get_field('whats_next') ?: [];

        if (array_filter($whatsNext)) {
            return $whatsNext;
        }

        return $this->preview && ! get_field('items') ? $this->example['whats_next'] : null;
    }

    /**
     * The block field group.
     */
    public function fields(): array
    {
        $fields = Builder::make('timeline');

        $fields
            ->addText('intro', [
                'label' => 'Opening circle text',
                'instructions' => 'Shown in the black circle at the top of the timeline.',
                'default_value' => "Take a look at what we've been up to",
            ])
            ->addFlexibleContent('items', [
                'label' => 'Timeline items',
                'button_label' => 'Add timeline item',
            ])
                ->addLayout('image', ['label' => 'Image'])
                    ->addImage('image', [
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                    ])
                ->addLayout('card', ['label' => 'Image + text card'])
                    ->addImage('image', [
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                    ])
                    ->addText('title')
                    ->addTextarea('text', ['rows' => 3])
                ->addLayout('stat', ['label' => 'Stat'])
                    ->addText('number', [
                        'label' => 'Figure',
                        'instructions' => 'e.g. 48%',
                        'required' => true,
                    ])
                    ->addTextarea('text', ['rows' => 3])
                ->addLayout('text', ['label' => 'Text'])
                    ->addWysiwyg('text', [
                        'tabs' => 'visual',
                        'toolbar' => 'minimal',
                        'media_upload' => false,
                        'required' => true,
                    ])
                ->addLayout('quote', ['label' => 'Quote'])
                    ->addTextarea('quote', ['rows' => 3, 'required' => true])
                    ->addText('citation', ['label' => 'Attribution'])
            ->endFlexibleContent()
            ->addGroup('whats_next', [
                'label' => "What's next",
                'instructions' => 'Optional closing panel, always shown at the very end of the timeline.',
            ])
                ->addText('title', ['default_value' => "What's next?"])
                ->addWysiwyg('text', [
                    'tabs' => 'visual',
                    'toolbar' => 'minimal',
                    'media_upload' => false,
                ])
                ->addLink('link')
            ->endGroup();

        return $fields->build();
    }
}
