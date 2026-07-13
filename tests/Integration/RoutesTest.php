<?php

use Kirby\Data\Yaml;

test('Event API returns 404 for non-existent event', function () {
    // Seed events parent page
    seed_page('termine', 'events');

    $response = run_route('event-api/event/non-existent-event');
    expect($response->code())->toBe(404);

    $data = json_decode($response->body(), true);
    expect($data)->toHaveKey('success', false);
    expect($data)->toHaveKey('error', 'Event not found');
});

test('Event API returns event details successfully', function () {
    // Seed tags
    seed_site_content([
        'tags' => Yaml::encode([
            [
                'customuuid' => 'uuid-zeltlager',
                'name' => 'Zeltlager',
                'alternatives' => ['Zela', 'Camp'],
            ],
        ]),
    ]);

    // Seed events parent page
    $eventsPage = seed_page('termine', 'events');
    putenv('KIRBY_EVENTS_USE_MOCK=true');

    // Get a valid child slug from the mock service
    $firstChild = $eventsPage->children()->first();
    expect($firstChild)->not->toBeNull();
    $slug = $firstChild->slug();

    // Call the API endpoint
    $response = run_route("event-api/event/{$slug}");
    expect($response->code())->toBe(200);

    $data = json_decode($response->body(), true);
    expect($data)->toHaveKey('success', true);
    expect($data)->toHaveKey('event');
    expect($data['event'])->toHaveKey('title', $firstChild->title()->value());
    expect($data['event'])->toHaveKey('slug', $slug);
});

test('Short camp slug redirects to full path', function () {
    // Seed camp pages
    $campsPage = seed_page('freizeiten', 'camps');

    // Seed a child camp page under freizeiten
    $camp = seed_page('sommerfreizeit-2027', 'camp', [], 'freizeiten');

    // Run route for the short slug (root level)
    $response = run_route('sommerfreizeit-2027');

    // Verify it issues a 301 redirect
    expect($response->code())->toBe(301);

    $locationObj = $response->headers()['Location'] ?? $response->headers()['location'] ?? null;
    expect($locationObj)->not->toBeNull();
    $location = (string) $locationObj;
    expect($location)->toContain('/freizeiten/sommerfreizeit-2027');
});
