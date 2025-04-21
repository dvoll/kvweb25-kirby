<?php

/**
 * @var \Kirby\Cms\Block $block
 *
 * @var \Kirby\Cms\File|null $image
 * @var string|null $title
 * @var string|null $text
 * @var string|null $url
 */

use Kirby\Toolkit\A;
use Kirby\Toolkit\Html;

$sizes = [
    // TODO: check
    // '(min-width: 40rem) vw', // 640
    '(min-width: 48rem) 189px', // 768
    '(min-width: 64rem) 331px', // 1024
    '(min-width: 80rem) 474px', // 1280
    '(min-width: 96rem) 616px', // 1536
    '100vw'
]

?>
<div class="card flex items-stretch justify-between md:basis-[28rem] max-w-[32rem] md:grow">
    <div class="self-center py-6 pl-6 md:basis-1/2">
        <h2 class="heading-lv3 mb-3"><?= $title ?></h2>
        <?php if (!empty($text)): ?>
            <p class="typo text-sm line-clamp-3 md:line-clamp-none">
                <?= $text ?>
            </p>
        <?php endif; ?>
        <a <?= Html::attr([
                'href' => $url,
                'class' => 'btn btn--secondary mt-6 md:mt-9 mr-2',
                'aria-label' => 'Mehr über unsere Arbeit'
            ]) ?>>Zur Seite &#8222;<?= $buttonTitle ?? $title ?>&#8220;<?= snippet('elements/icon') ?></a>
    </div>
    <?php if (!empty($image)): ?>
        <?= snippet(
            'picture',
            [
                'image' => $image,
                'cropRatio' => 4 / 3,
                'sizes' => A::join($sizes, ', '),
                'preset' => 'teaser',
                'class' => 'w-1/3 md:basis-1/2 min-w-[8rem] block',
                'responsive' => true,
                'imgClass' => 'angled-cut md:min-h-[16rem]',
            ]
        ); ?>
    <?php endif; ?>

</div>
