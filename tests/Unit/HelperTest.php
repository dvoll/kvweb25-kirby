<?php

use dvll\Sitepackage\Helpers\Helper;

test('Helper::getEnv returns correct boolean and string representations', function () {
    putenv('TEST_VAR_TRUE=true');
    expect(Helper::getEnv('TEST_VAR_TRUE'))->toBeTrue();

    putenv('TEST_VAR_FALSE=false');
    expect(Helper::getEnv('TEST_VAR_FALSE'))->toBeFalse();

    putenv('TEST_VAR_EMPTY=empty');
    expect(Helper::getEnv('TEST_VAR_EMPTY'))->toBe('');

    putenv('TEST_VAR_NULL=null');
    expect(Helper::getEnv('TEST_VAR_NULL'))->toBeNull();

    putenv('TEST_VAR_QUOTED="hello"');
    expect(Helper::getEnv('TEST_VAR_QUOTED'))->toBe('hello');

    expect(Helper::getEnv('TEST_VAR_NONEXISTENT', 'default'))->toBe('default');
});

test('Helper::ensureUniqueCustomUuids ensures uuids are unique and generated', function () {
    $input = [
        ['name' => 'item 1', 'customuuid' => 'uuid-1'],
        ['name' => 'item 2', 'customuuid' => ''],
        ['name' => 'item 3', 'customuuid' => 'uuid-1'], // duplicate
        ['name' => 'item 4'], // missing key
    ];

    $result = Helper::ensureUniqueCustomUuids($input);

    expect($result)->toHaveCount(4);
    expect($result[0]['customuuid'])->toBe('uuid-1'); // should keep original
    expect($result[1]['customuuid'])->not->toBeEmpty();
    expect($result[2]['customuuid'])->not->toBe('uuid-1'); // duplicate resolved
    expect($result[3]['customuuid'])->not->toBeEmpty();

    // All UUIDs must be unique
    $uuids = array_column($result, 'customuuid');
    expect(array_unique($uuids))->toHaveCount(4);
});
