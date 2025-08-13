<?php

/**
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
];

// $linkObject = $block->content()->get('link')->toObject();

?>
<div class="dvll-block dvll-block--centered flex flex-col-reverse md:flex-row gap-8 md:gap-6 md:justify-center">
    <div class="md:grid grid-cols-1 grid-rows-[1fr_auto_2fr] md:max-w-[400px] md:basis-1/2">
        <div class="row-start-2 md:py-4 lg:pr-6">
            <h2 class="heading-h2 mb-6"><?= $block->title()->escape() ?></h2>
            <div class="typo typo--rte">
                <?= $block->description()->kirbytext() ?>
            </div>
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
            'class' => 'md:max-w-[400px] md:basis-1/2',
            'imgClass' => 'rounded-md',
        ]
    ); ?>
</div>
