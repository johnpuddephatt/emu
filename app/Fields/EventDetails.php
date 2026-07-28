<?php

namespace App\Fields;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Field;

class EventDetails extends Field
{
    /**
     * The field group.
     */
    public function fields(): array
    {
        $fields = Builder::make('event_details', [
            'title' => 'Event details',
            'position' => 'side',
        ]);

        $fields
            ->addDateTimePicker('start', [
                'label' => 'Starts',
                'required' => true,
                'display_format' => 'D j M Y g:i a',
                'return_format' => 'Y-m-d H:i:s',
                'first_day' => 1,
            ])
            ->addDateTimePicker('end', [
                'label' => 'Ends',
                'display_format' => 'D j M Y g:i a',
                'return_format' => 'Y-m-d H:i:s',
                'first_day' => 1,
            ])
            ->addText('location', [
                'label' => 'Location',
            ]);

        $fields->setLocation('post_type', '==', 'event');

        return $fields->build();
    }
}
