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
    '(min-width: 64.5rem) 275px', // 1032
    '(min-width: 57rem) 175px', // 912
    '(min-width: 49.5rem) 200px', // 792
    '(min-width: 28.125rem) 280px', // 450
    '200px'
]

?>
<div class="card card--with-hover relative @container">
    <a <?= Html::attr([
            'href' => $url,
            'class' => 'absolute inset-0 z-1',
            'aria-hidden' => 'true',
            'tabindex' => '-1',
            'title' => 'Zur Seite: ' . ($buttonTitle ?? $title),
        ]) ?>></a>
    <div class="card__layout h-full @min-card-lg:grid-cols-[1fr_1fr] @min-card-xl:grid-cols-[3fr_1fr]">
        <div class="area-main self-center py-5 pl-5 @min-card-md:py-6 @min-card-md:pl-6">
            <h3 class="heading-lv3 mb-3"><?= $title ?></h3>
            <?php if (!empty($text)): ?>
                <p class="typo text-sm line-clamp-3 md:line-clamp-none">
                    <?= $text ?>
                </p>
            <?php endif; ?>
            <a <?= Html::attr([
                    'href' => $url,
                    'class' => 'btn btn--secondary mt-6 md:mt-9 mr-2',
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
                    'class' => 'area-image angled-cut origin-right @min-card-lg:origin-center',
                    'responsive' => true,
                    'imgClass' => 'w-full',
                ]
            ); ?>
        <?php else: ?>
            <div class="area-image angled-cut origin-right">
                <div class="bg-offwhite w-full h-full flex flex-col items-center justify-center">
                    <?= snippet('elements/icon', [
                        'class' => 'text-tertiary size-12 ml-8 @min-card-lg:origin-center',
                    ]) ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
