<?php

use dvll\KirbyWiki\WikiAccess;
use Kirby\Cms\App;
use Kirby\Cms\File;
use Kirby\Http\Response;

App::plugin('dvll/kirby-wiki', [
    'blueprints' => [
        'fields/wiki-blocks' => __DIR__ . '/blueprints/fields/wiki-blocks.yml',
        'fields/wiki-downloads' => __DIR__ . '/blueprints/fields/wiki-downloads.yml',
        'fields/wiki-links-downloads' => __DIR__ . '/blueprints/fields/wiki-links-downloads.yml',
        'files/wiki-download' => __DIR__ . '/blueprints/files/wiki-download.yml',
        'blocks/special--link-download-block-wiki' => __DIR__ . '/blocks/special--link-download-block-wiki/special--link-download-block-wiki.yml',
        'blocks/teaser-links-downloads-wiki' => __DIR__ . '/blocks/teaser-links-downloads-wiki/teaser-links-downloads-wiki.yml',
    ],
    'snippets' => [
        'blocks/special--link-download-block-wiki' => __DIR__ . '/blocks/special--link-download-block-wiki/special--link-download-block-wiki.php',
        'blocks/teaser-links-downloads-wiki' => __DIR__ . '/blocks/teaser-links-downloads/teaser-links-downloads.php',
    ],
    'components' => [
        'file::url' => function (App $kirby, File $file): string {
            return WikiAccess::protectedDownloadUrl($file) ?? $file->mediaUrl();
        },
    ],
    'fileMethods' => [
        'frontendUrl' => function (): string {
            return WikiAccess::protectedDownloadUrl($this) ?? $this->mediaUrl();
        },
    ],
    'hooks' => [
        'file.create:after' => function (File $file) {
            WikiAccess::purgePublicCopies($file);

            return $file;
        },
        'file.update:after' => function (File $newFile, File $oldFile) {
            WikiAccess::purgePublicCopies($newFile, $oldFile);

            return $newFile;
        },
        'file.replace:after' => function (File $newFile, File $oldFile) {
            WikiAccess::purgePublicCopies($newFile, $oldFile);

            return $newFile;
        },
        'file.changeName:after' => function (File $newFile, File $oldFile) {
            WikiAccess::purgePublicCopies($newFile, $oldFile);

            return $newFile;
        },
        'file.changeTemplate:after' => function (File $newFile, File $oldFile) {
            WikiAccess::purgePublicCopies($newFile, $oldFile);

            return $newFile;
        },
        'file.delete:before' => function (File $file) {
            WikiAccess::purgePublicCopies($file);

            return $file;
        },
    ],
    'routes' => function (App $kirby) {
        return [
            [
                'pattern' => 'wiki-download/(:any)',
                'action' => function (string $fileId) use ($kirby) {
                    $file = WikiAccess::findProtectedWikiFile($fileId, $kirby);

                    if ($file === null || WikiAccess::currentVisitorCanAccessPage($file->page(), $kirby) === false) {
                        return new Response('Not found', 'text/plain', 404, [
                            'Cache-Control' => 'private, no-store, no-cache, must-revalidate',
                            'Pragma' => 'no-cache',
                            'X-Robots-Tag' => 'noindex, nofollow, noarchive',
                            'X-Content-Type-Options' => 'nosniff',
                        ]);
                    }

                    return Response::file($file->root(), [
                        'headers' => [
                            'Cache-Control' => 'private, no-store, no-cache, must-revalidate',
                            'Pragma' => 'no-cache',
                            'X-Robots-Tag' => 'noindex, nofollow, noarchive',
                            'X-Content-Type-Options' => 'nosniff',
                        ],
                    ]);
                },
            ],
            [
                'pattern' => 'wiki-logout',
                'action' => function () use ($kirby) {
                    WikiAccess::clearGuestAccess($kirby);

                    go($kirby->page('wiki')?->url() ?? $kirby->site()->url());
                },
            ],
        ];
    },
]);