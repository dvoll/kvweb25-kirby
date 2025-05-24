<?php

/**
 * @var \Kirby\Cms\File|null $image
 * @var string|null $title
 * @var string|null $text
 * @var string|null $url
 */

use Kirby\Toolkit\Html;

$teaser = $teaser ?? false;
$buttonText = $buttonText ?? 'Beitrag anzeigen';

?>
<div class="card card--with-hover flex flex-col px-5 pt-5 pb-4 @min-card-md:px-6 @min-card-md:pb-4 @min-card-md:pt-6 relative <?= $class ?? '' ?> <?php e($teaser, 'bg-secondary justify-center items-center', 'justify-between') ?>">
    <a <?= Html::attr([
            'href' => $url,
            'class' => 'absolute inset-0',
            'aria-hidden' => 'true',
            'tabindex' => '-1',
            'title' => 'Zum Beitrag: ' . $title,
        ]) ?>></a>
    <div class="">
        <h3 class="heading-lv3 pr-4 <?php e($teaser, 'text-gray-600 text-center') ?>"><?= $title ?></h3>
        <?php if (!empty($text)): ?>
            <p class="max-w-96 text-sm text-contrast mt-2 <?php e($teaser, 'text-gray-600 text-center') ?>">
                <?= $text ?>
            </p>
        <?php endif; ?>
    </div>
    <div class="flex justify-between gap-4 mt-4 items-center">
        <?php if (!empty($date)): ?>
            <span class="text-sm text-gray-500"><?= Html::encode($date) ?></span>
        <?php endif; ?>
        <a <?= Html::attr([
                'href' => $url,
                'class' => 'btn btn--ghost ml-auto ' . ($teaser ? 'text-gray-600 text-center' : ''),
                'aria-label' => 'Zum Beitrag: ' . $title,
            ]) ?>><?= $buttonText ?><?= snippet('elements/icon') ?></a>
    </div>
</div>
