<?php

namespace App\View\Composers;

use Illuminate\Support\Collection;
use Roots\Acorn\View\Composer;

class Funders extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'components.funders',
    ];

    /**
     * Data to be passed to the view.
     *
     * Logos come in three kinds, each managed from Appearance → Funders:
     * workstream supporters (only on the workstreams they're tagged with),
     * core funders, and memberships/accreditations. The last two show on
     * every page. Groups come back in display order, empty ones dropped.
     */
    public function with(): array
    {
        $logos = $this->logos();

        $workstream = is_singular('workstream') ? get_queried_object_id() : null;

        $groups = collect([
            [
                'key' => 'workstream',
                'heading' => $workstream
                    ? sprintf(__('Our %s supporters and partners', 'sage'), html_entity_decode(get_the_title($workstream)))
                    : null,
                'logos' => $workstream
                    ? $logos->filter(fn($logo) => $logo['type'] === 'workstream'
                        && in_array($workstream, $logo['workstreams'], true))
                    : collect(),
            ],
            [
                'key' => 'funder',
                'heading' => $this->heading('funders_heading', __('With support from', 'sage')),
                'fallback' => __('Funders and supporters', 'sage'),
                'logos' => $logos->where('type', 'funder'),
            ],
            [
                'key' => 'member',
                'heading' => $this->heading('members_heading', __('Members of and accredited by', 'sage')),
                'fallback' => __('Memberships and accreditations', 'sage'),
                'logos' => $logos->where('type', 'member'),
            ],
        ])->filter(fn($group) => $group['logos']->isNotEmpty())->values();

        return [
            'groups' => $groups,
        ];
    }

    /**
     * A group heading from the options page. A field that's never been
     * saved reads as null, and gets the default; one cleared on purpose
     * reads as an empty string, and hides the heading.
     */
    protected function heading(string $field, string $default): string
    {
        return get_field($field, 'option') ?? $default;
    }

    /**
     * Every logo that's complete and still in date, with its sizing data.
     */
    protected function logos(): Collection
    {
        $today = current_time('Y-m-d');

        /*
         * Rows without both a name and a logo are skipped — a half-filled
         * row in the admin shouldn't render an empty box on every page of
         * the site. Rows saved before the "Show as" field existed have no
         * type, and count as core funders.
         */
        $logos = collect(get_field('funders', 'option') ?: [])
            ->filter(fn($logo) => ! empty($logo['name']) && ! empty($logo['logo']))
            ->filter(fn($logo) => empty($logo['show_until']) || $logo['show_until'] >= $today)
            ->map(fn($logo) => [
                ...$logo,
                'type' => ($logo['type'] ?? '') ?: 'funder',
                'workstreams' => array_map('intval', (array) ($logo['workstreams'] ?? [])),
            ]);

        /*
         * This renders on every page, so pull the attachments and their
         * metadata in one go — left to itself each logo costs two queries.
         */
        if ($logos->isNotEmpty()) {
            _prime_post_caches($logos->pluck('logo')->all(), false, true);
        }

        return $logos->map(function ($logo) {
            /*
             * Intrinsic size drives the sizing maths in common/_funders.css:
             * the aspect ratio, plus the height multiplier that would give
             * this logo the same area as a square one. The square root is
             * done here rather than in CSS so the strip doesn't depend on
             * `sqrt()` support. SVGs (and anything else WordPress can't
             * measure) come back without dimensions; those fall back to the
             * CSS defaults and are held in by the max-width there.
             */
            [, $width, $height] = array_pad(wp_get_attachment_image_src($logo['logo'], 'full') ?: [], 3, 0);

            $ratio = $width && $height ? $width / $height : null;

            return [
                ...$logo,
                'ratio' => $ratio ? round($ratio, 4) : null,
                'area' => $ratio ? round(1 / sqrt($ratio), 4) : null,
            ];
        });
    }
}
