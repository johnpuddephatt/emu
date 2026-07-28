<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Page extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'page',
    ];

    /**
     * Data to be passed to the view.
     *
     * The content is rendered up front so the "In this section" jump
     * links can be generated from its h2 headings, with matching ids
     * injected into the markup.
     */
    public function with(): array
    {
        $content = apply_filters('the_content', get_the_content(null, false, get_post()));
        $headings = $this->headings($content);

        return [
            'toc' => collect($headings)->pluck('text', 'id')->all(),
            'content' => $this->injectHeadingIds($content, $headings),
        ];
    }

    /**
     * The top-level h2 headings in the rendered content, each resolved
     * to an id — the block's HTML anchor when one is set, otherwise
     * derived from the heading text.
     */
    protected function headings(string $content): array
    {
        preg_match_all('/<h2([^>]*)>(.*?)<\/h2>/si', $content, $matches, PREG_SET_ORDER);

        return collect($matches)
            ->map(fn($match) => [
                'match' => $match[0],
                'attributes' => $match[1],
                'text' => trim(wp_strip_all_tags($match[2])),
                'id' => preg_match('/\bid=["\']([^"\']+)["\']/', $match[1], $id)
                    ? $id[1]
                    : sanitize_title_with_dashes(trim(wp_strip_all_tags($match[2]))),
            ])
            ->filter(fn($heading) => $heading['text'] && $heading['id'])
            ->unique('id')
            ->values()
            ->all();
    }

    /**
     * Add an id to each heading that doesn't already have one.
     */
    protected function injectHeadingIds(string $content, array $headings): string
    {
        foreach ($headings as $heading) {
            if (preg_match('/\bid=/', $heading['attributes'])) {
                continue;
            }

            $content = str_replace(
                $heading['match'],
                preg_replace('/^<h2/', sprintf('<h2 id="%s"', esc_attr($heading['id'])), $heading['match']),
                $content,
            );
        }

        return $content;
    }
}
