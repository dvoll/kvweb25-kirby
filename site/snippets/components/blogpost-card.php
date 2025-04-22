<?php

/**
 * @var \Kirby\Cms\File|null $image
 * @var string|null $title
 * @var string|null $text
 * @var string|null $url
 */

use Kirby\Toolkit\A;
use Kirby\Toolkit\Html;
use Kirby\Toolkit\Str;

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
<div class="card flex flex-col px-6 py-4 pt-6 justify-between relative group/card hover:ring-1 ring-primary <?= $class ?? '' ?>">
    <a <?= Html::attr([
            'href' => $url,
            'class' => 'absolute inset-0',
            'aria-hidden' => 'true',
            'tabindex' => '-1',
            'title' => 'Zum Beitrag: ' . $title,
        ]) ?>></a>
    <div class="">
        <h3 class="heading-lv2"><?= $title ?></h3>
        <?php if (!empty($text)): ?>
            <p class="text-sm text-contrast mt-2">
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
                'class' => 'btn btn--ghost ml-auto',
                'aria-label' => 'Zum Beitrag: ' . $title,
            ]) ?>>Beitrag anzeigen<?= snippet('elements/icon') ?></a>
    </div>
</div>
