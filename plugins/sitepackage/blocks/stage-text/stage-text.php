<?php

/**
 * @var \Kirby\Cms\Page $page
 * @var \Kirby\Cms\Block $block
 */

?>
<div class="stage-text dvll-block dvll-block--wide flex md:grid grid-cols-subgrid dvll-first">
    <div class="col-start-1 col-end-5">
        <div class="md:py-4">
            <h1 class="heading-title mb-6"><?= $page->myTitle() ?></h1>
            <p class="typo">
                <?= $block->description()->kirbytext() ?>
            </p>
            <?= snippet('components/breadcrumb', ['class' => 'mt-3']); ?>
        </div>
    </div>
</div>
