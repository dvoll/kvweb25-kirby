<?php

use dvll\KirbyEvents\Models\EventPage;
use dvll\KirbyEvents\Models\EventsPage;
use Kirby\Cms\App;
use Kirby\Uuid\Uuid;

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
]);
