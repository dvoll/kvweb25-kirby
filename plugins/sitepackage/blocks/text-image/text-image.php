<?php

/**
 * @var \Kirby\Cms\Block $block
 */

use Kirby\Toolkit\A;

$sizes = [
    // TODO: check
    // '(min-width: 40rem) vw', // 640
    '(min-width: 48rem) 389px', // 768
    '(min-width: 64rem) 531px', // 1024
    '(min-width: 80rem) 674px', // 1280
    '(min-width: 96rem) 816px', // 1536
    '100vw'
];

// $linkObject = $block->content()->get('link')->toObject();

?>
<div class="dvll-block col-span-full lg:col-start-2 lg:col-end-9 flex flex-col md:flex-row gap-y-4 md:gap-x-6 md:justify-center">
    <div class="md:grid grid-cols-1 grid-rows-[1fr_auto_2fr] md:max-w-[400px] basis-1/2">
        <div class="row-start-2 md:py-4 lg:pr-6">
            <h2 class="heading-lv2 mb-6"><?= $block->title()->escape() ?></h2>
            <p class="typo">
                <?= $block->description()->escape() ?>
            </p>
            <?php if ($block->link()->isNotEmpty() && $block->linkLabel()->isNotEmpty()): ?>
                <a <?= Html::attr([
                        'href' => $block->link()->toUrl(),
                        'class' => 'btn btn--secondary mt-9 mr-2',
                    ]) ?>><?= $block->linkLabel()->escape(); ?>
                    <?= snippet('elements/icon') ?></a>
            <?php endif; ?>


        </div>
    </div>
    <?= snippet(
        'picture',
        [
            'image' => $block->image()->toFile(),
            'cropRatio' => 4 / 3,
            'sizes' => A::join($sizes, ', '),
            'preset' => 'default',
            'class' => 'md:max-w-[400px] basis-1/2'
        ]
    ); ?>
</div>
