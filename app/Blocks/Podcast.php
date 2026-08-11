<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class Podcast extends Block
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
    public $name = 'Podcast';

    /**
     * The block slug.
     *
     * @var string
     */
    public $slug = 'podcast';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A playable list of the latest podcast episodes, pulled from Buzzsprout.';

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
    public $icon = 'microphone';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['podcast', 'episodes', 'audio', 'buzzsprout', 'chronicles'];

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
            'placeholder' => 'East Marshian Chronicles',
        ],
        'core/paragraph' => [
            'placeholder' => 'Podcasting from the edge of things, on Grimsby’s historic East Marsh.',
        ],
    ];

    /**
     * How long the Buzzsprout response is cached for, in seconds.
     *
     * @var int
     */
    protected $cacheLifetime = HOUR_IN_SECONDS;

    /**
     * Data to be passed to the block before rendering.
     */
    public function with(): array
    {
        return [
            'episodes' => $this->episodes(),
            'links' => get_field('links') ?: [],
        ];
    }

    /**
     * The episodes to display, newest first.
     */
    public function episodes(): array
    {
        $episodes = $this->fetchEpisodes(
            (string) (get_field('podcast_id') ?: ''),
            (string) (get_field('api_token') ?: ''),
        );

        usort($episodes, fn($a, $b) => strtotime($b['published_at'] ?? '') <=> strtotime($a['published_at'] ?? ''));

        $episodes = array_slice($episodes, 0, (int) (get_field('number') ?: 5));

        return array_map(fn($episode) => [
            'title' => $episode['title'] ?? '',
            'number' => $episode['episode_number'] ?? null,
            'season' => $episode['season_number'] ?? null,
            'audio' => $episode['audio_url'] ?? '',
            'duration' => $this->duration($episode['duration'] ?? 0),
            'published' => $episode['published_at'] ?? '',
            'upcoming' => strtotime($episode['published_at'] ?? '') > current_time('timestamp', true),
        ], $episodes);
    }

    /**
     * Fetch the show's episodes from the Buzzsprout API, cached in a transient.
     *
     * Failures are cached briefly too, so a Buzzsprout outage doesn't mean an
     * outbound request on every page load.
     */
    protected function fetchEpisodes(string $podcastId, string $apiToken): array
    {
        if (! $podcastId || ! $apiToken) {
            return [];
        }

        $key = 'emu_podcast_' . md5($podcastId . $apiToken);

        if (is_array($cached = get_transient($key))) {
            return $cached;
        }

        $response = wp_remote_get(sprintf(
            'https://www.buzzsprout.com/api/%s/episodes.json?api_token=%s',
            rawurlencode($podcastId),
            rawurlencode($apiToken),
        ), ['timeout' => 10]);

        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            set_transient($key, [], 5 * MINUTE_IN_SECONDS);

            return [];
        }

        $episodes = json_decode(wp_remote_retrieve_body($response), true);

        if (! is_array($episodes)) {
            $episodes = [];
        }

        // Drop private episodes — they're not publicly playable.
        $episodes = array_values(array_filter($episodes, fn($episode) => empty($episode['private'])));

        set_transient($key, $episodes, $this->cacheLifetime);

        return $episodes;
    }

    /**
     * Format a duration in seconds as `m:ss` (or `h:mm:ss`).
     */
    protected function duration(int $seconds): string
    {
        if ($seconds <= 0) {
            return '';
        }

        return $seconds >= HOUR_IN_SECONDS
            ? gmdate('G:i:s', $seconds)
            : ltrim(gmdate('i:s', $seconds), '0');
    }

    /**
     * The block field group.
     */
    public function fields(): array
    {
        $fields = Builder::make('podcast');

        $fields
            ->addText('podcast_id', [
                'label' => 'Buzzsprout podcast ID',
                'instructions' => 'The numeric show ID — the number in your Buzzsprout dashboard URL.',
                'default_value' => '1764181',
            ])
            ->addText('api_token', [
                'label' => 'Buzzsprout API token',
                'instructions' => 'Found under Buzzsprout → Settings → API Tokens.',
                'default_value' => '76a69249f3fc975766b5faf5d4b449c7',
            ])
            ->addNumber('number', [
                'label' => 'Number of episodes',
                'default_value' => 5,
                'min' => 1,
                'max' => 20,
            ])
            ->addRepeater('links', [
                'label' => '"Listen on" links',
                'button_label' => 'Add link',
                'layout' => 'table',
            ])
                ->addLink('link', [
                    'label' => 'Link',
                    'return_format' => 'array',
                ])
            ->endRepeater();

        return $fields->build();
    }
}
