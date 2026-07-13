<?php

use Kirby\Cms\Page;
use Kirby\Data\Data;

uses()
    ->beforeEach(function () {
        // Clean and prepare the temporary directories before each test
        test_cleanup_directory(TEST_TMP_DIR);
        @mkdir(TEST_TMP_DIR, 0777, true);
        @mkdir(TEST_CONTENT_DIR, 0777, true);
        @mkdir(TEST_STORAGE_DIR, 0777, true);
        @mkdir(TEST_ACCOUNTS_DIR, 0777, true);
        @mkdir(TEST_CACHE_DIR, 0777, true);
        @mkdir(TEST_SESSIONS_DIR, 0777, true);

        $kirby = new Kirby\Cms\App([
            'options' => [
                'tobimori.seo' => [
                    'files' => [
                        'parent' => 'site',
                    ],
                ],
            ],
            'roots' => [
                'index'    => TEST_TMP_DIR,
                'base'     => dirname(__DIR__),
                'content'  => TEST_CONTENT_DIR,
                'site'     => dirname(__DIR__) . '/site',
                'storage'  => TEST_STORAGE_DIR,
                'accounts' => TEST_ACCOUNTS_DIR,
                'cache'    => TEST_CACHE_DIR,
                'sessions' => TEST_SESSIONS_DIR,
            ],
        ]);
        Kirby\Cms\App::instance($kirby);
        $kirby->impersonate('kirby');

        // Seed the images page which is required by the fields/image.yml blueprint
        seed_page('images', 'images', [
            'uuid' => 'images',
        ]);
    })
    ->afterEach(function () {
        @restore_error_handler();
        @restore_exception_handler();
    })
    ->afterAll(function () {
        test_cleanup_directory(TEST_TMP_DIR);
    })
    ->in(__DIR__);

/**
 * Seed a page in the mock content directory
 * @param array<string, mixed> $content
 */
function seed_page(string $slug, string $template, array $content = [], ?string $parent = null): Page
{
    $kirby = kirby();
    $props = [
        'slug'     => $slug,
        'template' => $template,
        'content'  => $content,
        'draft'    => false, // Publish it by default for ease of testing routes
    ];
    if ($parent) {
        $props['parent'] = $kirby->page($parent);
    }

    return Page::create($props);
}

/**
 * Seed site content (e.g. global settings, tags, contacts)
 * @param array<string, mixed> $content
 */
function seed_site_content(array $content): void
{
    $file = TEST_CONTENT_DIR . '/site.txt';
    Data::write($file, $content, 'txt');
}

/**
 * Run a route in Kirby and return the output/response
 */
function run_route(string $path, string $method = 'GET'): Kirby\Http\Response
{
    $kirby = kirby();

    return $kirby->render($path, $method);
}

/**
 * Helper to clean up directory recursively
 */
function test_cleanup_directory(string $dir): void
{
    if (!is_dir($dir)) {
        return;
    }
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST,
    );
    foreach ($files as $fileinfo) {
        $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
        @$todo($fileinfo->getRealPath());
    }
    @rmdir($dir);
}
