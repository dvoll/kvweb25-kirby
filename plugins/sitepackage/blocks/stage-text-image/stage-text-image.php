<?php

/**
 * @var \Kirby\Cms\Page $page
 * @var \Kirby\Cms\Block $block
 */

use Kirby\Toolkit\A;

$sizes = [
    // TODO: check
    // '(min-width: 40rem) vw', // 640
    '(min-width: 96rem) 816px', // 1536
    '(min-width: 80rem) 674px', // 1280
    '(min-width: 64rem) 531px', // 1024
    '(min-width: 48rem) 389px', // 768
    '100vw'
]

?>
<div class="dvll-block dvll-block--wide flex flex-col-reverse gap-y-4 md:grid grid-cols-subgrid dvll-first">
    <div class="col-start-1 col-end-4 md:grid grid-cols-1 grid-rows-[1fr_auto_2fr]">
        <div class="row-start-2 md:py-4 lg:pr-6">
            <h1 class="heading-title mb-6"><?= $page->myTitle() ?></h1>
            <p class="typo">
                <?= $block->description()->escape() ?>
            </p>
            <?= snippet('components/breadcrumb', ['class' => 'mt-3']); ?>
        </div>
    </div>
    <?= snippet(
        'picture',
        [
            'image' => $block->image()->toFile(),
            'cropRatio' => 3 / 2,
            'sizes' => A::join($sizes, ', '),
            'preset' => 'default',
            'class' => 'col-span-6'
        ]
    ); ?>
</div>
