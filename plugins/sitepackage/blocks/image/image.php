<?php

/**
 * @var \Kirby\Cms\Block $block
 */

use Kirby\Toolkit\A;

$ratio = $block->ratio()->value();

if ($ratio !== 'auto') {
    $parts = explode('/', $ratio);
    $calculatedRatio = (int)$parts[0] / (int)$parts[1];
} else {
    $calculatedRatio = null;
}

$sizes = [
    // '(min-width: 40rem) vw', // 640
    '(min-width: 48rem) 389px', // 768
    '(min-width: 64rem) 531px', // 1024
    '(min-width: 80rem) 674px', // 1280
    '(min-width: 96rem) 816px', // 1536
    '100vw'
];

$caption = $block->caption()->isNotEmpty() ? $block->caption()->html() : ($block->image()->isNotEmpty() && $block->image()->toFile()->caption()->isNotEmpty() ? $block->image()->toFile()->caption()->html() : null);

?>
<div class="dvll-block dvll-block--narrow">
    <figure>
        <?= snippet(
            'picture',
            [
                'image' => $block->image()->toFile(),
                'cropRatio' => $calculatedRatio,
                'sizes' => A::join($sizes, ', '),
                'preset' => 'default',
                'alt' => $block->alt()->isNotEmpty() ? $block->alt()->value() : null,
                // 'responsive' => true,
            ]
        ); ?>
        <?php if (!empty($caption)) : ?>
        <figcaption class="text-sm text-contrast mt-2 mx-4">
            <?= $caption ?>
        </figcaption>
        <?php endif; ?>
    </figure>
</div>
