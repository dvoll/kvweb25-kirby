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
]

?>
<div class="dvll-section dvll-section--narrow">
    <?= snippet(
        'picture',
        [
            'image' => $block->image()->toFile(),
            'cropRatio' => $calculatedRatio,
            'sizes' => A::join($sizes, ', '),
            'preset' => 'default',
            // 'responsive' => true,
        ]
    ); ?>
</div>
