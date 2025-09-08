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

$shouldBeCropped = $block->content()->get('crop')->isEmpty() || $block->content()->get('crop')->value() !== 'nocrop'; // Default is crop

$caption = $block->caption()->isNotEmpty() ? $block->caption()->html() : ($block->image()->isNotEmpty() && $block->image()->toFile()->caption()->isNotEmpty() ? $block->image()->toFile()->caption()->html() : null);

$orderReverse = $block->content()->get('imagePosition')->isNotEmpty() && $block->content()->get('imagePosition')->value() === 'left';

// $linkObject = $block->content()->get('link')->toObject();

?>
<div class="dvll-block dvll-block--centered flex flex-col-reverse <?= $orderReverse ? 'md:flex-row-reverse' : 'md:flex-row' ?> gap-8 md:gap-6 md:justify-center">
    <div class="md:grid grid-cols-1 grid-rows-[1fr_auto_2fr] md:basis-1/2">
        <div class="row-start-2 <?= $orderReverse ? 'lg:pl-6' : 'lg:pr-6' ?>">
            <?php if ($block->title()->isNotEmpty()): ?>
                <h2 class="heading-h2 text-balance mb-6"><?= $block->title()->escape() ?></h2>
            <?php endif; ?>
            <div class=" typo typo--reading-size typo--rte">
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
    <figure class="md:basis-1/2">
        <?= snippet(
            'picture',
            [
                'image' => $block->image()->toFile(),
                'cropRatio' => $shouldBeCropped ?  4 / 3 : null,
                'sizes' => A::join($sizes, ', '),
                'preset' => 'default',
                'alt' => $block->alt()->isNotEmpty() ? $block->alt()->value() : null,
                'imgClass' => 'w-full rounded-md object-contain aspect-[4/3]',
                'responsive' => !$shouldBeCropped
            ]
        ); ?>
        <?php if (!empty($caption)) : ?>
            <figcaption class="text-body text-sm text-contrast mt-2 mx-4 max-w-[40rem]">
                <?= $caption ?>
            </figcaption>
        <?php endif; ?>
    </figure>
</div>
