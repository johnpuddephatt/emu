<?php

namespace App\Options;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Options as Field;

class EventsArchive extends Field
{
    /**
     * The option page menu name.
     *
     * @var string
     */
    public $name = 'Archive page';

    /**
     * The option page document title.
     *
     * @var string
     */
    public $title = 'Events archive page';

    /**
     * The slug of another admin page to be used as a parent.
     *
     * Nests this under the Events post type menu.
     *
     * @var string|null
     */
    public $parent = 'edit.php?post_type=event';

    /**
     * The option page field group.
     */
    public function fields(): array
    {
        $fields = Builder::make('events_archive');

        $fields
            ->addPostObject('events_archive_page', [
                'label' => 'Events archive page',
                'instructions' => 'Pick a page whose title, featured image and excerpt supply the header shown at the top of the events archive (/events/). The page itself redirects to the archive, so it will not appear as a separate URL.',
                'post_type' => ['page'],
                'return_format' => 'id',
                'allow_null' => 1,
                'ui' => 1,
            ]);

        return $fields->build();
    }
}
