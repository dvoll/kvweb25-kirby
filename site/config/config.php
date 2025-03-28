<?php

use dvll\Sitepackage\Menu;
use dvll\Sitepackage\Helper;

require_once __DIR__ . '/../../vendor/vlucas/phpdotenv/src/Dotenv.php';

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(realpath(__DIR__ . '/../../'));
$dotenv->load();

return [
    'panel' => [
        'language' => 'de',
    ],
    'cache' => [
        'pages' => [
            'active' => Helper::getEnv('KIRBY_CACHE'),
            'prefix' => 'pages',
        ],
        'uuid' => [
            'active' => Helper::getEnv('KIRBY_CACHE'),
            'prefix' => 'uuid',
        ],
    ],
    'debug' => Helper::getEnv('KIRBY_DEBUG'),
    'routes' => [],
    'tobimori.seo' => [
        'lang' => 'de_DE',
        'canonicalBase' => Helper::getEnv("APP_URL"),
        'files' => [
            'parent' => 'site.find("page://images")',
            'template' => 'image'
        ],
    ],
    /** Email */
    'email' => [
        'transport' => [
            'type' => 'smtp',
            'host' => Helper::getEnv("KIRBY_MAIL_HOST"),
            'port' => (int) Helper::getEnv("KIRBY_MAIL_PORT"),
            'security' => Helper::getEnv('KIRBY_MAIL_SECURITY'),
            'auth' => Helper::getEnv('KIRBY_MAIL_AUTH'),
            'username' => Helper::getEnv("KIRBY_MAIL_USER"),
            'password' => Helper::getEnv("KIRBY_MAIL_PASS")
        ]
    ],
    'auth' => [
        'methods' => ['password', 'password-reset'],
        'challenge' => [
            'email' => [
                'from' => Helper::getEnv("KIRBY_MAIL_FROM"),
                'fromName' => 'CVJM Kreisverband Bünde e.V.',
            ]
        ]
    ],
    'panel.menu' => function () {
        $menu = [
            'site' => Menu::site('Start'),
        ];

        $user = kirby()->user();
        if ($user && $user->role()->permissions()->for('pages', 'read')) {
            $menu[] = '-';
            $menu['website'] = Menu::page('Website', 'email', page('home'));
        }
        if ($user && $user->role()->permissions()->for('pages', 'read')) {
            $menu[] = '-';
            $menu['images'] = Menu::page('Bilder', 'images', page('page://images'));
        }

        $menu[] = '-';
        $menu[] = 'users';
        $menu[] = 'system';

        return $menu;
    },
    'ready' => fn () => [
        'panel' => [
            'favicon' => option('debug') ? 'assets/panel/favicon-dev.svg' : 'assets/panel/favicon-live.svg',
        ],
    ]
];
