<?php

/**
 * @var \Kirby\Cms\Block $block
 */

$ratio = $block->ratio()->or(null);
if ($ratio !== null) {
    $parts = explode('/', $ratio);
    $calculatedRatio = (int)$parts[0] / (int)$parts[1];
} else {
    $calculatedRatio = null;
}

?>
<div class="dvll-block dvll-block--narrow">
    <?= snippet('picture', ['image' => $block->image()->toFile(), 'ratio' => $calculatedRatio, 'sizes' => '(min-width: 1200px) 25vw, (min-width: 900px) 33vw, (min-width: 600px) 50vw 100vw', 'crop' => true]); ?>
</div>
