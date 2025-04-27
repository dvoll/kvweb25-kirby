<?php

/** @var \Kirby\Cms\Block $block */
?>

<div class="dvll-block dvll-block--narrow mb-4">
    <blockquote>
        <?= $block->text() ?>
        <?php if ($block->citation()->isNotEmpty()): ?>
            <footer>
                <?= $block->citation() ?>
            </footer>
        <?php endif ?>
    </blockquote>
</div>
