<?php

namespace App\Options;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Options as Field;

class SocialMedia extends Field
{
    /**
     * The option page menu name.
     *
     * @var string
     */
    public $name = 'Social media';

    /**
     * The option page document title.
     *
     * @var string
     */
    public $title = 'Social media accounts';

    /**
     * The slug of another admin page to be used as a parent.
     *
     * Sits under Appearance, alongside Menus.
     *
     * @var string|null
     */
    public $parent = 'themes.php';

    /**
     * The option page field group.
     */
    public function fields(): array
    {
        $fields = Builder::make('social_media');

        $fields
            ->addRepeater('social_media', [
                'label' => 'Accounts',
                'instructions' => 'Shown as icons in the footer and at the bottom of the menu. Drag the handles to reorder them.',
                'layout' => 'table',
                'button_label' => 'Add account',
            ])
            ->addSelect('platform', [
                'label' => 'Platform',
                'choices' => config('social.platforms'),
                'allow_null' => 0,
            ])
            ->addUrl('url', [
                'label' => 'Link',
                'instructions' => 'The full address of the account, including https://',
            ])
            ->endRepeater();

        return $fields->build();
    }
}
