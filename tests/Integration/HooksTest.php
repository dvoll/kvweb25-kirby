<?php

use Kirby\Data\Yaml;

test('site.update:after hook automatically adds unique custom UUIDs to contacts and tags', function () {
    // 1. Initially seed the site with contacts and tags that lack custom UUIDs
    // (We write them directly to the file first, so they exist before the update)
    seed_site_content([
        'title' => 'CVJM Kreisverband Bünde',
        'contacts' => Yaml::encode([
            [
                'name' => 'John Doe',
                'customuuid' => '',
            ],
            [
                'name' => 'Jane Smith',
            ],
        ]),
        'tags' => Yaml::encode([
            [
                'name' => 'Zeltlager',
                'customuuid' => '',
            ],
            [
                'name' => 'Posaunen',
            ],
        ]),
    ]);

    // Refresh site content in memory to load the seeded values
    $site = kirby()->site();

    // 2. Perform a site update to trigger the site.update:after hook
    // We update the title, which will cause the hook to read the contacts and tags,
    // generate UUIDs for them, and save them.
    $site->update([
        'title' => 'CVJM Kreisverband Bünde (Updated)',
    ]);

    // 3. Reload site and verify the fields now contain custom UUIDs
    $updatedSite = kirby()->site()->content();

    /** @var Kirby\Cms\Field $contactsField */
    $contactsField = $updatedSite->get('contacts');
    $contacts = $contactsField->yaml();

    /** @var Kirby\Cms\Field $tagsField */
    $tagsField = $updatedSite->get('tags');
    $tags = $tagsField->yaml();

    expect((array) $contacts)->toHaveCount(2);
    $contactUuid0 = (string) ($contacts[0]['customuuid'] ?? '');
    $contactUuid1 = (string) ($contacts[1]['customuuid'] ?? '');
    expect($contactUuid0)->not->toBeEmpty();
    expect($contactUuid1)->not->toBeEmpty();
    expect($contactUuid0)->not->toBe($contactUuid1);

    expect((array) $tags)->toHaveCount(2);
    $tagUuid0 = (string) ($tags[0]['customuuid'] ?? '');
    $tagUuid1 = (string) ($tags[1]['customuuid'] ?? '');
    expect($tagUuid0)->not->toBeEmpty();
    expect($tagUuid1)->not->toBeEmpty();
    expect($tagUuid0)->not->toBe($tagUuid1);
});
