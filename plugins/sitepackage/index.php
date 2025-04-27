<?php

use Kirby\Cms\App;
use Kirby\Data\Yaml;
use Kirby\Filesystem\F;

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
        'blocks/teaser-blogposts'      => __DIR__ . '/blocks/teaser-blogposts/teaser-blogposts.php',
        'blocks/teaser-pages'      => __DIR__ . '/blocks/teaser-pages/teaser-pages.php',
        'blocks/text-image'      => __DIR__ . '/blocks/text-image/text-image.php',
        ]
]);
