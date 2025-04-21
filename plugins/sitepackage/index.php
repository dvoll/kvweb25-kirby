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
			if (($user = $kirby->user()) && ($user->isAdmin() || $user->role()->name() === 'super-editor') ) {
				return Yaml::decode(F::read(dirname(__DIR__) . '/sitepackage/blueprints/tabs/global-settings.yml'));
			} else {
                return [
                    'label' => '-',
                ];
            }
		},
        'blocks/picture'      => __DIR__ . '/blocks/picture/picture.yml',
        'blocks/text-image'      => __DIR__ . '/blocks/text-image/text-image.yml',
        'blocks/teaser-pages'      => __DIR__ . '/blocks/teaser-pages/teaser-pages.yml',
        'blocks/stage-text-image'      => __DIR__ . '/blocks/stage-text-image/stage-text-image.yml',
	],
    'snippets'   => [
        'blocks/picture'      => __DIR__ . '/blocks/picture/picture.php',
        'blocks/text-image'      => __DIR__ . '/blocks/text-image/text-image.php',
        'blocks/teaser-pages'      => __DIR__ . '/blocks/teaser-pages/teaser-pages.php',
        'blocks/stage-text-image'      => __DIR__ . '/blocks/stage-text-image/stage-text-image.php',
    ]
]);
