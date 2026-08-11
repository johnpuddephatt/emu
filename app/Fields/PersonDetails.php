<?php

namespace App\Fields;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Field;

class PersonDetails extends Field
{
    /**
     * The field group.
     */
    public function fields(): array
    {
        $fields = Builder::make('person_details', [
            'title' => 'Person details',
            'position' => 'side',
        ]);

        $fields
            ->addText('role_title', [
                'label' => 'Job title',
                'instructions' => 'Shown under the name, e.g. "Chief Storyteller".',
            ]);

        $fields->setLocation('post_type', '==', 'person');

        return $fields->build();
    }
}
