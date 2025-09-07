<?php

use dvll\KirbyEvents\Models\EventPage;
use dvll\KirbyEvents\Models\EventsPage;
use Kirby\Cms\App;

App::plugin('dvll/kirby-events', [
    'options' => [
        'cache' => true
    ],
    'blueprints' => [
        'pages/event' => __DIR__ . '/blueprints/pages/event.yml',
        'pages/events' => __DIR__ . '/blueprints/pages/events.yml'
    ],
    'pageModels' => [
        'events' => EventsPage::class,
        'event' => EventPage::class,
    ],
    'templates' => [
        'event' => __DIR__ . '/templates/event.php',
        'event.ics' => __DIR__ . '/templates/event.ics.php',
        'events' => __DIR__ . '/templates/events.php'
    ],
    'areas' => [
        'dvll-events-area' => function (App $kirby) {
            return [
                'buttons' => [
                    'refresh-events' => function () {
                        return [
                            'icon'     => 'refresh',
                            'text'     => 'Termine aktualisieren',
                            'dialog' => 'dvll-events/refresh'
                        ];
                    }
                ],
                'dialogs' => [
                    'dvll-events/refresh' => [
                        // the load event is creating a GET route at
                        // `/panel/dialogs/{pattern}`;
                        // it returns the setup for the dialog, including
                        // used component, buttons, props, etc.
                        'load' => function () {
                            return [
                                'component' => 'k-text-dialog',
                                'props' => [
                                    'text' => 'Mit dem Klicken auf "OK" werden erneut alle Termine aus dem verknüpften Google Kalender abgerufen.',
                                ]
                            ];
                        },
                        'submit' => function () use ($kirby) {
                            $cache = $kirby->cache('dvll.kirby-events');
                            $cache->set('events', null); // Invalidate cache
                            kirbylog('[dvll.kirby-events] Event cache invalidated through panel. Fetching new events from Google Calendar.');
                            return true;
                        }
                    ]
                ]
            ];
        }
    ]
]);
