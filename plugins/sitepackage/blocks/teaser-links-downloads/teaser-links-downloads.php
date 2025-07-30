<?php

/**
 * @var \Kirby\Cms\Block $block
 * @var DefaultPage $page
 */

$items = $block->items()->toBlocks();

?>

<?php if (count($items) > 0): ?>
<div class="dvll-block dvll-block--wide">
        <div class="dvll-block dvll-block--wide">
            <ul class="grid grid-cols-(--dvll-card-grid-cols--small) gap-3 w-full">
                <?php foreach ($items as $item): ?>
                    <li class="">
                        <?= $item ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>
