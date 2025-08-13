<?php

/** @var \Kirby\Cms\Block $block */

$level = $block->level()->or('h2');
?>

<div class="dvll-block dvll-block--narrow dvll-block--gap-sm">
    <<?= $level ?> class="heading-<?= $level ?>"><?= $block->text() ?></<?= $level ?>>
</div>
