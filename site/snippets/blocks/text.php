<?php

/** @var \Kirby\Cms\Block $block */
?>

<div class="dvll-block dvll-block--narrow dvll-block--gap-sm typo typo--rte">
    <?= $block->text()->permalinksToUrls(); ?>
</div>
