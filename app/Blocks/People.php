<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class People extends Block
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
    public $name = 'People';

    /**
     * The block slug.
     *
     * @var string
     */
    public $slug = 'people';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A grid of staff and trustee profiles, optionally opening a full bio in a modal.';

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
    public $icon = 'groups';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['people', 'staff', 'team', 'trustees', 'profiles'];

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
            'placeholder' => 'Meet the team',
        ],
    ];

    /**
     * The block preview example data.
     *
     * @var array
     */
    public $example = [
        'people' => [
            ['id' => 0, 'name' => 'Billy Dasein', 'role' => 'Chief Storyteller', 'photo' => null, 'bio' => '', 'anchor' => 'billy-dasein'],
            ['id' => 0, 'name' => 'Josie Moon', 'role' => 'People and Place Ambassador', 'photo' => null, 'bio' => '', 'anchor' => 'josie-moon'],
            ['id' => 0, 'name' => 'Lauren Davidson', 'role' => 'Communications', 'photo' => null, 'bio' => '', 'anchor' => 'lauren-davidson'],
        ],
    ];

    /**
     * Data to be passed to the block before rendering.
     */
    public function with(): array
    {
        return [
            'people' => $this->people(),
            'columns' => (int) (get_field('columns') ?: 3),
            'show_modal' => (bool) get_field('show_modal'),
        ];
    }

    /**
     * The people to display, in the order set on the Person edit screens.
     */
    public function people(): array
    {
        $query = [
            'post_type' => 'person',
            'numberposts' => -1,
            'orderby' => ['menu_order' => 'ASC', 'title' => 'ASC'],
        ];

        if ($types = get_field('role_types')) {
            $query['tax_query'] = [
                [
                    'taxonomy' => 'role_type',
                    'field' => 'term_id',
                    'terms' => $types,
                ],
            ];
        }

        $people = collect(get_posts($query))->map(fn($person) => [
            'id' => $person->ID,
            'name' => get_the_title($person),
            'role' => get_field('role_title', $person->ID) ?: '',
            'photo' => get_post_thumbnail_id($person) ?: null,
            'bio' => trim($person->post_content) ? do_blocks($person->post_content) : '',
            'anchor' => $person->post_name,
        ])->all();

        return $people ?: ($this->preview ? $this->example['people'] : []);
    }

    /**
     * The block field group.
     */
    public function fields(): array
    {
        $fields = Builder::make('people');

        $fields
            ->addTaxonomy('role_types', [
                'label' => 'Role types',
                'instructions' => 'Leave empty to show everyone.',
                'taxonomy' => 'role_type',
                'field_type' => 'multi_select',
                'add_term' => false,
                'save_terms' => false,
                'load_terms' => false,
                'return_format' => 'id',
                'multiple' => true,
                'allow_null' => true,
            ])
            ->addSelect('columns', [
                'label' => 'Columns',
                'instructions' => 'On large screens — the grid steps down on smaller ones.',
                'choices' => [2 => '2', 3 => '3', 4 => '4'],
                'default_value' => 3,
                'return_format' => 'value',
            ])
            ->addTrueFalse('show_modal', [
                'label' => 'Show full profile in a modal',
                'instructions' => 'Adds a "Read bio" button to anyone with content on their profile.',
                'ui' => true,
                'default_value' => 1,
            ]);

        return $fields->build();
    }
}
