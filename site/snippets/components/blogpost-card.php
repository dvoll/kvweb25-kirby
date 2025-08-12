<?php

/**
 * @var \Kirby\Cms\File|null $image
 * @var string|null $title
 * @var string|null $text
 * @var string|null $url
 * @var string|null $date
 * @var bool|null $dynamicContent - If true, content will be filled via Alpine.js
 */

use Kirby\Toolkit\Html;

$teaser = $teaser ?? false;
$buttonText = $buttonText ?? 'Beitrag anzeigen';
$dynamicContent = $dynamicContent ?? false;

?>
<div class="card card--with-hover flex flex-col px-5 pt-5 pb-4 @min-card-md:px-6 @min-card-md:pb-4 @min-card-md:pt-6 relative <?= $class ?? '' ?> <?php e($teaser, 'bg-secondary justify-center items-center', 'justify-between') ?>">
    <?php if ($dynamicContent): ?>
        <a :href="blogpost.url"
            class="absolute inset-0"
            aria-hidden="true"
            tabindex="-1"
            :title="'Zum Beitrag: ' + blogpost.title"></a>
    <?php else: ?>
        <a <?= Html::attr([
                'href' => $url,
                'class' => 'absolute inset-0',
                'aria-hidden' => 'true',
                'tabindex' => '-1',
                'title' => 'Zum Beitrag: ' . $title,
            ]) ?>></a>
    <?php endif; ?>

    <div class="">
        <?php if ($dynamicContent): ?>
            <h3 class="heading-lv3 pr-4 <?php e($teaser, 'text-gray-600 text-center') ?>" x-text="blogpost.title"></h3>
            <p class="max-w-96 text-sm text-contrast mt-2 <?php e($teaser, 'text-gray-600 text-center') ?>" x-text="blogpost.text"></p>
        <?php else: ?>
            <h3 class="heading-lv3 pr-4 <?php e($teaser, 'text-gray-600 text-center') ?>"><?= $title ?></h3>
            <?php if (!empty($text)): ?>
                <p class="max-w-96 text-sm text-contrast mt-2 <?php e(strlen($title) > 29, 'line-clamp-2')?> <?php e($teaser, 'text-gray-600 text-center') ?>">
                    <?= $text ?>
                </p>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <div class="flex justify-between gap-4 mt-4 items-center">
        <?php if ($dynamicContent): ?>
            <span class="text-sm text-gray-500" x-show="blogpost.date" x-text="blogpost.date"></span>
            <a :href="blogpost.url"
                class="btn btn--ghost ml-auto <?= $teaser ? 'text-gray-600 text-center' : '' ?>"
                :aria-label="'Zum Beitrag: ' + blogpost.title">
                <?= $buttonText ?><?= snippet('elements/icon') ?>
            </a>
        <?php else: ?>
            <?php if (!empty($date)): ?>
                <span class="text-sm text-gray-500"><?= Html::encode($date) ?></span>
            <?php endif; ?>
            <a <?= Html::attr([
                    'href' => $url,
                    'class' => 'btn btn--ghost ml-auto ' . ($teaser ? 'text-gray-600 text-center' : ''),
                    'aria-label' => 'Zum Beitrag: ' . $title,
                ]) ?>><?= $buttonText ?><?= snippet('elements/icon') ?></a>
        <?php endif; ?>
    </div>
</div>
