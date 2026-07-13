<?php

use dvll\KirbyEvents\Models\EventPage;
use dvll\KirbyEvents\Models\EventsPage;
use Kirby\Data\Yaml;

test('EventsPage generates correct child pages from calendar service', function () {
    // 1. Seed site tags structure
    seed_site_content([
        'title' => 'CVJM Kreisverband',
        'tags' => Yaml::encode([
            [
                'customuuid' => 'uuid-zeltlager',
                'name' => 'Zeltlager',
                'alternatives' => ['Zela', 'Camp'],
            ],
            [
                'customuuid' => 'uuid-posaunen',
                'name' => 'Posaunen',
                'alternatives' => ['Posaunenchor', 'Konzert'],
            ],
        ]),
    ]);

    // 2. Create the parent "termine" events page
    $eventsPage = seed_page('termine', 'events');
    expect($eventsPage)->toBeInstanceOf(EventsPage::class);

    // 3. Set env variables for test run (using mock calendar service)
    putenv('KIRBY_EVENTS_USE_MOCK=true');

    // 4. Retrieve children
    $children = $eventsPage->children();

    // Since mock service generates several events, we should have a non-empty children list
    expect($children->count())->toBeGreaterThan(0);

    // Get the first child and inspect its model
    $firstChild = $children->first();
    expect($firstChild)->toBeInstanceOf(EventPage::class);
    expect($firstChild->slug())->not->toBeEmpty();
});

test('EventsPage explicit category bracket extraction matches correctly', function () {
    // Seed tags
    seed_site_content([
        'tags' => Yaml::encode([
            [
                'customuuid' => 'uuid-special-camp',
                'name' => 'Zeltlager',
                'alternatives' => ['Zela'],
            ],
        ]),
    ]);

    // Seed events parent page
    $eventsPage = seed_page('termine', 'events');
    putenv('KIRBY_EVENTS_USE_MOCK=true');

    $children = $eventsPage->children();

    // Find the Mädchenzeltlager Sommer 2024 event
    $event = $children->filter(fn ($p) => str_contains($p->title()->value(), 'Mädchenzeltlager'))->first();

    expect($event)->not->toBeNull();

    // Assert category UUID matches the Zeltlager tag
    expect($event->content()->get('category')->value())->toBe('uuid-special-camp');

    // Assert bracket prefix [Zeltlager] was stripped from the description
    expect($event->content()->get('description')->value())->toBe('Das Zeltlager für Mädchen bietet viele Aktivitäten und gemeinsames Zelten.');
});
