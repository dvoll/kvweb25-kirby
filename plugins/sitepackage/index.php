<?php

use dvll\Sitepackage\Models\LayoutWithContactBlock;
use dvll\Sitepackage\Models\TeaserBlogpostsBlock;
use Kirby\Cms\App;
use Kirby\Data\Yaml;
use Kirby\Filesystem\F;
use Kirby\Uuid\Uuid;

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
    ],
    'blockModels' => [
        'teaser-blogposts' => TeaserBlogpostsBlock::class,
        'layout-with-contact' => LayoutWithContactBlock::class,
    ],
    'hooks' => [
        'site.update:after' => function (Kirby\Cms\Site $newSite, Kirby\Cms\Site $oldSite) {
            // Generate custom uuids for the site structured field "contacts" to enable selecting them with a multiselect
            /** @var Kirby\Cms\Field $contactsField*/
            $contactsField = $newSite->content()->get('contacts');
            $contactsStructure = $contactsField->yaml();
            foreach ($contactsStructure as $index => &$item) {
                // Check if a valid UUID already exists
                if (empty($item['customuuid'])) {
                    $item['customuuid'] = Uuid::generate();
                } else {
                    // Check for duplicates in newSite; If there are any, generate a new UUID for the last one
                    $duplicates = array_filter($contactsStructure, function ($innerItem, $innerIndex) use ($item, $index) {
                        return $innerIndex !== $index && $innerItem['customuuid'] === $item['customuuid'];
                    }, ARRAY_FILTER_USE_BOTH);

                    if (!empty($duplicates)) {
                        $item['customuuid'] = Uuid::generate();
                    }
                }
            }
            $newSite->save([
                'contacts' => Data::encode($contactsStructure, "yaml"),
            ]);
        }
    ],
]);
