<?php

/** @var \Kirby\Cms\Block $block */
$crop    = $block->crop()->isTrue();
$ratio   = $block->ratio()->or('auto');
$images  = $block->images();

?>

<?= snippet('components/gallery', compact('images', 'crop', 'ratio')) ?>
