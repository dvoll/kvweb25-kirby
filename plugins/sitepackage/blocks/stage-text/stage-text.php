<?php

/**
 * @var \Kirby\Cms\Page $page
 * @var \Kirby\Cms\Block $block
 */

?>
<div class="stage-text dvll-block dvll-block--wide flex md:grid grid-cols-subgrid pt-4 pb-8 md:py-8">
    <div class="col-start-1 col-end-5">
        <div class="md:py-4">
            <h1 class="heading-title mb-6 text-balance"><?= $page->myTitle() ?></h1>
            <div class="typo text-lg leading-6 typo--rte">
                <?= $block->description()->kirbytext() ?>
            </div>
            <?= snippet('components/breadcrumb', ['class' => 'mt-3']); ?>
        </div>
    </div>
</div>
