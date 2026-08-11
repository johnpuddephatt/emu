<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class Video extends Block
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
    public $name = 'Video';

    /**
     * The block slug.
     *
     * @var string
     */
    public $slug = 'video';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A captioned video still that opens the video full screen when clicked.';

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
    public $icon = 'video-alt3';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['video', 'youtube', 'vimeo', 'embed', 'film'];

    /**
     * The default block mode.
     *
     * @var string
     */
    public $mode = 'preview';

    /**
     * The supported block features. No alignment: this is designed to sit in
     * a column alongside text, at whatever width its container gives it.
     *
     * @var array
     */
    public $supports = [
        'align' => false,
        'anchor' => true,
        'mode' => true,
        'multiple' => true,
    ];

    /**
     * How long an oEmbed lookup is cached for.
     *
     * @var int
     */
    protected $cacheLifetime = WEEK_IN_SECONDS;

    /**
     * The block preview example data.
     *
     * @var array
     */
    public $example = [
        'title' => 'Having fun together',
        'description' => 'Using art, music and creativity to bring people closer',
        'thumbnail' => null,
        'thumbnail_url' => '',
        'embed' => '',
        'html' => '',
    ];

    /**
     * Data to be passed to the block before rendering.
     */
    public function with(): array
    {
        $url = (string) (get_field('url') ?: '');
        $data = $url ? $this->oembed($url) : [];

        return [
            'title' => get_field('title') ?: ($data['title'] ?? ''),
            'description' => get_field('description') ?: '',
            'thumbnail' => get_field('thumbnail') ?: null,
            'thumbnail_url' => $data['thumbnail_url'] ?? '',
            // A player URL we can autoplay when the modal opens; providers we
            // don't know fall back to whatever markup oEmbed handed us.
            'embed' => $this->embedUrl($url),
            'html' => $data['html'] ?? '',
        ];
    }

    /**
     * The oEmbed payload for a URL — title, thumbnail and provider markup —
     * cached so the front end never waits on the provider.
     */
    protected function oembed(string $url): array
    {
        $key = 'emu_oembed_' . md5($url);

        if (is_array($cached = get_transient($key))) {
            return $cached;
        }

        $data = _wp_oembed_get_object()->get_data($url);

        $data = $data ? (array) $data : [];

        set_transient($key, $data, $data ? $this->cacheLifetime : 5 * MINUTE_IN_SECONDS);

        return $data;
    }

    /**
     * An autoplaying player URL for the providers worth special-casing.
     * YouTube goes through the no-cookie domain.
     */
    protected function embedUrl(string $url): string
    {
        if (preg_match('#(?:youtube\.com/(?:watch\?(?:.*&)?v=|embed/|live/|shorts/)|youtu\.be/)([\w-]{11})#i', $url, $matches)) {
            return 'https://www.youtube-nocookie.com/embed/' . $matches[1] . '?autoplay=1&rel=0';
        }

        if (preg_match('#vimeo\.com/(?:video/)?(\d+)#i', $url, $matches)) {
            return 'https://player.vimeo.com/video/' . $matches[1] . '?autoplay=1';
        }

        return '';
    }

    /**
     * The block field group.
     */
    public function fields(): array
    {
        $fields = Builder::make('video');

        $fields
            ->addUrl('url', [
                'label' => 'Video URL',
                'instructions' => 'A YouTube or Vimeo link — or any provider WordPress can embed.',
                'required' => true,
            ])
            ->addImage('thumbnail', [
                'label' => 'Thumbnail',
                'instructions' => 'Leave empty to use the still the provider supplies, which is often small — an uploaded image will usually look better.',
                'return_format' => 'id',
                'preview_size' => 'medium',
            ])
            ->addText('title', [
                'label' => 'Title',
                'instructions' => 'Leave empty to use the video’s own title.',
            ])
            ->addTextarea('description', [
                'label' => 'Description',
                'rows' => 2,
                'new_lines' => '',
            ]);

        return $fields->build();
    }
}
