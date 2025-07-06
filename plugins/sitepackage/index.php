<?php

use dvll\Sitepackage\Models\LayoutWithContactBlock;
use dvll\Sitepackage\Models\TeaserBlogpostsBlock;
use Kirby\Cms\App;
use Kirby\Data\Yaml;
use Kirby\Filesystem\F;
use Kirby\Http\Uri;
use dvll\Sitepackage\Helpers\Helper;

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
        'blocks/quote'      => __DIR__ . '/blocks/quote/quote.yml',
        'blocks/stage-text'      => __DIR__ . '/blocks/stage-text/stage-text.yml',
        'blocks/stage-text-image'      => __DIR__ . '/blocks/stage-text-image/stage-text-image.yml',
        'blocks/stage-hero'      => __DIR__ . '/blocks/stage-hero/stage-hero.yml',
        'blocks/teaser-blogposts'      => __DIR__ . '/blocks/teaser-blogposts/teaser-blogposts.yml',
        'blocks/teaser-pages'      => __DIR__ . '/blocks/teaser-pages/teaser-pages.yml',
        'blocks/text-image'      => __DIR__ . '/blocks/text-image/text-image.yml',
        'blocks/text'      => __DIR__ . '/blocks/text/text.yml',
        'blocks/spacer'      => __DIR__ . '/blocks/spacer/spacer.yml',
    ],
    'snippets'   => [
        'blocks/gallery'      => __DIR__ . '/blocks/gallery/gallery.php',
        'blocks/heading'      => __DIR__ . '/blocks/heading/heading.php',
        'blocks/image'      => __DIR__ . '/blocks/image/image.php',
        'blocks/quote'      => __DIR__ . '/blocks/quote/quote.php',
        'blocks/stage-text'      => __DIR__ . '/blocks/stage-text/stage-text.php',
        'blocks/stage-text-image'      => __DIR__ . '/blocks/stage-text-image/stage-text-image.php',
        'blocks/stage-hero'      => __DIR__ . '/blocks/stage-hero/stage-hero.php',
        'blocks/teaser-blogposts'      => __DIR__ . '/blocks/teaser-blogposts/teaser-blogposts.php',
        'blocks/teaser-pages'      => __DIR__ . '/blocks/teaser-pages/teaser-pages.php',
        'blocks/text-image'      => __DIR__ . '/blocks/text-image/text-image.php',
        'blocks/text'      => __DIR__ . '/blocks/text/text.php',
        'blocks/spacer'      => __DIR__ . '/blocks/spacer/spacer.php',
    ],
    'blockModels' => [
        'teaser-blogposts' => TeaserBlogpostsBlock::class,
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
                // Redirects from short "freizeiten" slugs to the current structure
                'pattern' => '(:any)',
                'action'  => function ($slug) {
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
