<?php

use Kirby\CLI\CLI;
use Kirby\Cms\Page;
use Kirby\Data\Yaml;

return [
    'description' => 'Migration',
    'args' => [
        /* https://climate.thephpleague.com/arguments/ */
        'force' => [
            'longPrefix' => 'force',
            'description' => 'Force override of blocks',
            'default' => false,
            'noValue' => true,
        ]
    ],
    'command' => static function (CLI $cli): void {
        kirby()->impersonate('kirby');
        $cli->out('Migration kirby data...');

        $pages = site()->index(true)->filter('template', 'in', ['layout', 'blogposts', 'camps', 'camp', 'events']);
        foreach ($pages as $page) {
            if ($page->blocks()->isNotEmpty() && $cli->arg('force')){
                $cli->shout('Force override for Page ' . $page->title() . '');
                continue;
            } elseif ($page->blocks()->isNotEmpty()){
                $cli->info('[Skip] Page ' . $page->title() . ' has blocks, skipping...');
                continue;
            }
            $blocks = [];
            $first = true;
            foreach ($page->layouts()->toBlocks() as $layoutBlock) {
                if (!$first) {
                    $blocks[] = ["content" => [], "isHidden" => false, "type" => "spacer"];
                }
                $blocks = array_merge($blocks, $layoutBlock->col1()->toBlocks()->toArray());
                $first = false;
            }
            if (count($blocks) === 0) {
                $cli->whisper('[None] No blocks for page ' . $page->title() . '.');
                continue;
            }
            $cli->success('[Save] Saving new blocks for page ' . $page->title() . '.');
            $page->update([
                'blocks' => Yaml::encode($blocks),
                // 'layouts' => $page->layouts()->toLayouts()
            ]);
        }

        // if ($currentPage = page('contentpage')) {
        //     $cli->info('Updating ' . $currentPage->title() . ' ...');
        //     $blocks = [];
        //     $first = true;
        //     foreach ($currentPage->layouts()->toBlocks() as $layoutBlock) {
        //         if (!$first) {
        //             $blocks[] = ["content" => [], "isHidden" => false, "type" => "spacer"];
        //         }
        //         $blocks = A::merge($blocks, $layoutBlock->col1()->toBlocks()->toArray());
        //         $first = false;
        //     }
        //     $currentPage->update([
        //         'blocks' => Yaml::encode($blocks),
        //         // 'layouts' => $currentPage->layouts()->toLayouts()
        //     ]);
        // }

        $cli->success('Migration done!');
    }
];
