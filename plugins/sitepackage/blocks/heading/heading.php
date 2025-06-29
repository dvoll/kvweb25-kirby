<?php

/** @var \Kirby\Cms\Block $block */
?>

<div class="dvll-block dvll-block--narrow dvll-block--gap-sm">
    <<?= $level = $block->level()->or('h2') ?> class="heading-lv2"><?= $block->text() ?></<?= $level ?>>
</div>
