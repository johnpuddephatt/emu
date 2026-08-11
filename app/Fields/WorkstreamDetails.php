<?php

namespace App\Fields;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Field;

class WorkstreamDetails extends Field
{
    /**
     * The field group.
     */
    public function fields(): array
    {
        $fields = Builder::make('workstream_details', [
            'title' => 'Workstream details',
            'position' => 'side',
        ]);

        $fields
            ->addTextarea('strapline', [
                'label' => 'Strapline',
                'instructions' => 'One line summing the workstream up. Shown under the title in the page header, and on the Workstreams block’s cards.',
                'rows' => 2,
                'new_lines' => '',
            ])
            ->addTextarea('introduction', [
                'label' => 'Introduction',
                'instructions' => 'A short paragraph shown under the strapline in the page header.',
                'rows' => 4,
                'new_lines' => '',
            ])
            ->addTextarea('short_description', [
                'label' => 'Short description',
                'instructions' => 'Used in place of the strapline by the Workstreams block’s "Rows" layout. Falls back to the strapline when empty.',
                'rows' => 4,
                'new_lines' => '',
            ]);

        $fields->setLocation('post_type', '==', 'workstream');

        return $fields->build();
    }
}
