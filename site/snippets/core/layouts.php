<?php

/**
 * @var \Kirby\Cms\Page $page
 * @var array<string, mixed> $contacts
 */

use dvll\Sitepackage\Models\LayoutWithContactBlock;

/**
 * @var \Kirby\Content\Field $blocks
 */
$blocks = $page->content()->get('blocks');
?>

<section class="dvll-section">
    <div class="dvll-section__layout">

        <?php foreach ($blocks->toBlocks() as $block) : ?>
            <?php
            // Render the block using the block model if available
            if ($block->type() === 'spacer'): ?>

    </div>
</section>
<section class="dvll-section">
    <div class="dvll-section__layout">
            <?php else: ?>
                <?= $block ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</section>
