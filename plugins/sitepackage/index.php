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
	],
]);
