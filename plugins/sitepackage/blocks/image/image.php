<?php

/**
 * @var \Kirby\Cms\Block $block
 */

use Kirby\Toolkit\A;

$ratio = $block->ratio()->value();

if ($ratio !== 'auto' && $ratio !== '') {
    $parts = explode('/', $ratio);
    $calculatedRatio = (int)$parts[0] / (int)$parts[1];
} else {
    $calculatedRatio = null;
}

$sizes = [
    '(min-width: 96rem) 816px', // 1536
    '(min-width: 80rem) 674px', // 1280
    '(min-width: 64rem) 531px', // 1024
    '(min-width: 48rem) 389px', // 768
    '100vw'
];

$caption = $block->caption()->isNotEmpty() ? $block->caption()->html() : ($block->image()->isNotEmpty() && $block->image()->toFile()->caption()->isNotEmpty() ? $block->image()->toFile()->caption()->html() : null);

?>
<div class="dvll-block dvll-block--centered">
    <figure>
        <?= snippet(
            'picture',
            [
                'image' => $block->image()->toFile(),
                'cropRatio' => $calculatedRatio,
                'sizes' => A::join($sizes, ', '),
                'preset' => 'default',
                'alt' => $block->alt()->isNotEmpty() ? $block->alt()->value() : null,
                'imgClass' => !$calculatedRatio || $calculatedRatio > 1 ? 'w-full' : 'max-h-96 w-auto',
            ]
        ); ?>
        <?php if (!empty($caption)) : ?>
            <figcaption class="text-body text-sm text-contrast mt-2 mx-4 max-w-[40rem]">
                <?= $caption ?>
            </figcaption>
        <?php endif; ?>
    </figure>
</div>
