<?php

/**
 * MIT License
 *
 * Copyright (c) 2023 Tobias Möritz
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files(the "Software"), to deal
 *     in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and / or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 */

use Kirby\CLI\CLI;
use Kirby\Cms\Page;

return [
    'description' => 'Scaffold kirby',
    'args' => [],
    'command' => static function (CLI $cli): void {
        kirby()->impersonate('kirby');
        $cli->info('Scaffolding kirby-baukasten...');

        if (!page('home')) {
            $cli->info('Creating empty home page...');
            $page = Page::create([
                'slug' => 'home',
                'template' => 'home',
                'content' => [],
            ]);
            $page->changeStatus('listed');
        }

        if (!page('error')) {
            $cli->info('Creating empty error page...');
            $page = Page::create([
                'slug' => 'error',
                'template' => 'error',
                'content' => [],
            ]);
        }

        if (!page('blogposts')) {
            $cli->info('Creating empty blogposts page...');
            $page = Page::create([
                'slug' => 'blog',
                'template' => 'blogposts',
                'content' => [],
            ]);
        }

        if (!page('images')) {
            $cli->info('Creating images page...');
            $page = Page::create([
                'slug' => 'images',
                'template' => 'images',
                'content' => [
                    'uuid' => 'images',
                ],
            ]);
            $page->changeStatus('unlisted');
        }

        $cli->info('Scaffolding done!');
    }
];
