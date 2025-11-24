<?php

use dvll\Sitepackage\Helpers\CustomGd;
use dvll\Sitepackage\Helpers\CustomImageMagick;
use dvll\Sitepackage\Models\LayoutWithContactBlock;
use dvll\Sitepackage\Models\TeaserBlogpostsBlock;
use dvll\Sitepackage\Models\TeaserEventsBlock;
use Kirby\Cms\App;
use Kirby\Data\Yaml;
use Kirby\Filesystem\F;
use Kirby\Http\Uri;
use dvll\Sitepackage\Helpers\Helper;

Kirby\Image\Darkroom::$types['custom-im'] = CustomImageMagick::class;
Kirby\Image\Darkroom::$types['custom-gd'] = CustomGd::class;

App::plugin('dvll/sitepackage', [
    'blueprints' => [
        'programmatic/admin-tools' => function (App $kirby) {
            if (($user = $kirby->user()) && $user->isAdmin()) {
                return Yaml::decode(F::read(dirname(__DIR__, 2) . '/site/blueprints/tabs/admin-tools.yml'));
            } else {
                return [
                    'label' => '-',
                ];
            }
        },
        'programmatic/global-settings' => function (App $kirby) {
            if (($user = $kirby->user()) && ($user->isAdmin() || $user->role()->name() === 'super-editor')) {
                return Yaml::decode(F::read(dirname(__DIR__) . '/sitepackage/blueprints/tabs/global-settings.yml'));
            } else {
                return [
                    'label' => '-',
                ];
            }
        },
        'blocks/gallery'      => __DIR__ . '/blocks/gallery/gallery.yml',
        'blocks/heading'      => __DIR__ . '/blocks/heading/heading.yml',
        'blocks/image'      => __DIR__ . '/blocks/image/image.yml',
        'blocks/video'      => __DIR__ . '/blocks/video/video.yml',
        'blocks/quote'      => __DIR__ . '/blocks/quote/quote.yml',
        'blocks/stage-text'      => __DIR__ . '/blocks/stage-text/stage-text.yml',
        'blocks/stage-text-image'      => __DIR__ . '/blocks/stage-text-image/stage-text-image.yml',
        'blocks/stage-hero'      => __DIR__ . '/blocks/stage-hero/stage-hero.yml',
        'blocks/teaser-blogposts'      => __DIR__ . '/blocks/teaser-blogposts/teaser-blogposts.yml',
        'blocks/teaser-events'      => __DIR__ . '/blocks/teaser-events/teaser-events.yml',
        'blocks/teaser-pages'      => __DIR__ . '/blocks/teaser-pages/teaser-pages.yml',
        'blocks/text-image'      => __DIR__ . '/blocks/text-image/text-image.yml',
        'blocks/text'      => __DIR__ . '/blocks/text/text.yml',
        'blocks/spacer'      => __DIR__ . '/blocks/spacer/spacer.yml',
        'blocks/facts'      => __DIR__ . '/blocks/facts/facts.yml',
        'blocks/teaser-links-downloads'      => __DIR__ . '/blocks/teaser-links-downloads/teaser-links-downloads.yml',
        'blocks/team'      => __DIR__ . '/blocks/team/team.yml',
    ],
    'snippets'   => [
        'blocks/gallery'      => __DIR__ . '/blocks/gallery/gallery.php',
        'blocks/heading'      => __DIR__ . '/blocks/heading/heading.php',
        'blocks/image'      => __DIR__ . '/blocks/image/image.php',
        'blocks/video'      => __DIR__ . '/blocks/video/video.php',
        'blocks/quote'      => __DIR__ . '/blocks/quote/quote.php',
        'blocks/stage-text'      => __DIR__ . '/blocks/stage-text/stage-text.php',
        'blocks/stage-text-image'      => __DIR__ . '/blocks/stage-text-image/stage-text-image.php',
        'blocks/stage-hero'      => __DIR__ . '/blocks/stage-hero/stage-hero.php',
        'blocks/teaser-blogposts'      => __DIR__ . '/blocks/teaser-blogposts/teaser-blogposts.php',
        'blocks/teaser-events'      => __DIR__ . '/blocks/teaser-events/teaser-events.php',
        'blocks/teaser-pages'      => __DIR__ . '/blocks/teaser-pages/teaser-pages.php',
        'blocks/text-image'      => __DIR__ . '/blocks/text-image/text-image.php',
        'blocks/text'      => __DIR__ . '/blocks/text/text.php',
        'blocks/spacer'      => __DIR__ . '/blocks/spacer/spacer.php',
        'blocks/facts'      => __DIR__ . '/blocks/facts/facts.php',
        'blocks/teaser-links-downloads'      => __DIR__ . '/blocks/teaser-links-downloads/teaser-links-downloads.php',
        'blocks/team'      => __DIR__ . '/blocks/team/team.php',
    ],
    'blockModels' => [
        'teaser-blogposts' => TeaserBlogpostsBlock::class,
        'teaser-events' => TeaserEventsBlock::class,
        'layout-with-contact' => LayoutWithContactBlock::class,
    ],
    'hooks' => [
        'site.update:after' => function (Kirby\Cms\Site $newSite, Kirby\Cms\Site $oldSite) {

            /** @var Kirby\Cms\Field $contactsField*/
            $contactsField = $newSite->content()->get('contacts');
            $contactsStructureWithUuids = Helper::ensureUniqueCustomUuids($contactsField->yaml());

            /** @var Kirby\Cms\Field tagsField */
            $tagsField = $newSite->content()->get('tags');
            $tagsStructureWithUuids = Helper::ensureUniqueCustomUuids($tagsField->yaml());

            $newSite->save([
                'contacts' => Data::encode($contactsStructureWithUuids, "yaml"),
                'tags' => Data::encode($tagsStructureWithUuids, "yaml"),
            ]);
        }
    ],
    'routes' => function (\Kirby\Cms\App $kirby) {
        return [
            [
                // API endpoint for fetching event details
                'pattern' => 'event-api/event/(:all)',
                'action' => function ($eventSlug) use ($kirby) {
                    header('Content-Type: application/json');

                    try {
                        /** @var dvll\KirbyEvents\Models\EventPage|null $eventPage */
                        $eventPage = $kirby->page('termine')->find($eventSlug);

                        if (!$eventPage || !$eventPage->isPublished()) {
                            http_response_code(404);
                            return json_encode([
                                'success' => false,
                                'error' => 'Event not found'
                            ], JSON_UNESCAPED_UNICODE);
                        }

                        // Get calendar links
                        $eventLinks = \dvll\KirbyEvents\Models\EventPage::getCalendarLinks($eventPage);

                        // Get connected blogposts
                        $blogpostPages = $eventPage->getConnectedBlogposts();
                        $blogposts = [];
                        foreach ($blogpostPages as $blogpost) {
                            $blogposts[] = [
                                'title' => $blogpost->title()->value(),
                                'text' => $blogpost->text()->excerpt(120)->value(),
                                'url' => $blogpost->url()
                            ];
                        }

                        // Get event tag information
                        $tagInfo = $eventPage->getTagInfo();

                        // Prepare event data
                        $eventData = [
                            'title' => $eventPage->title()->value(),
                            'slug' => $eventPage->slug(),
                            'description' => $eventPage->content()->get('description')->value(),
                            'location' => $eventPage->content()->get('location')->value(),
                            'isAllDay' => $eventPage->isAllDayEvent(),
                            'hasMultipleDays' => $eventPage->hasMultipleDays(),
                            'startDate' => $eventPage->getStartDate()->format('c'),
                            'endDate' => $eventPage->getEndDateTime()->format('c'),
                            'endDateOnly' => date('c', $eventPage->getEndDateOnly(useCorrection: true)),
                            'calendarLinks' => $eventLinks,
                            'blogposts' => $blogposts,
                            'tag' => $tagInfo
                        ];

                        return json_encode([
                            'success' => true,
                            'event' => $eventData
                        ], JSON_UNESCAPED_UNICODE);

                    } catch (Exception $e) {
                        http_response_code(500);
                        return json_encode([
                            'success' => false,
                            'error' => 'Internal server error'
                        ], JSON_UNESCAPED_UNICODE);
                    }
                }
            ],
            [
                // Redirects from short "freizeiten" slugs to the current structure
                // Only match single word slugs, not paths that start with api/
                'pattern' => '([^/]+)',
                'action'  => function ($slug) {
                    // Skip if this looks like an API call or other system path
                    if (strpos($slug, 'api') === 0 || strpos($slug, 'panel') === 0 || strpos($slug, 'media') === 0) {
                        /**
                         * @var \Kirby\Http\Route $this
                         * @phpstan-ignore variable.undefined, varTag.variableNotFound
                         */
                        $this->next();
                        return;
                    }

                    if ($page = page('freizeiten')->find($slug)) {
                        go("freizeiten/{$page->slug()}", 301);
                    }

                    /**
                     * Calls the next middleware or handler in the stack.
                     *
                     * @var \Kirby\Http\Route $this
                     * @phpstan-ignore variable.undefined, varTag.variableNotFound
                     */
                    $this->next();
                }
            ],
            [
                // Searches the "termine" page for a specific event slug and redirects to the correct page set
                'pattern' => 'termine',
                'action' => function () use ($kirby) {
                    $eventSlug = get('event', null);
                    $eventPageSet = get('event-page-set', null);
                    if ($eventSlug && empty($eventPageSet)) {
                        $events = $kirby->page('termine')->children()->published()->sortBy('start', 'asc');
                        $selectedEvent = $events->filter(fn($event) => $event->slug() === $eventSlug)->first();
                        /** @var \Kirby\Cms\Page|null $selectedEvent */
                        if ($selectedEvent) {
                            $position = $selectedEvent->indexOf($events);
                            $customParams = array_merge(params(), ['page' => $position ? floor($position / 9) + 1 : 1]);
                            go(new Uri($kirby->url('current'), [
                                'params' => $customParams,
                                'query' => ['event' => $eventSlug, 'event-page-set' => 'true']
                            ]));
                        }
                    }
                    /**
                     * @var \Kirby\Http\Route $this
                     * @phpstan-ignore variable.undefined, varTag.variableNotFound
                     */
                    $this->next();
                }
            ]
        ];
    },
]);
