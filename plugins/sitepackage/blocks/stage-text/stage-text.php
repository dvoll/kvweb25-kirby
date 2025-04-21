<?php

/**
 * @var \Kirby\Cms\Page $page
 * @var \Kirby\Cms\Block $block
 */

?>
<div class="stage-text dvll-block dvll-block--wide flex flex-col-reverse gap-y-4 md:grid grid-cols-subgrid dvll-first">
    <div class="col-start-1 col-end-4 md:grid grid-cols-1 grid-rows-[1fr_auto_2fr]">
        <div class="row-start-2 md:py-4 lg:pr-6">
            <h1 class="heading-title mb-6"><?= $page->myTitle() ?></h1>
            <p class="typo">
                <?= $block->description()->escape() ?>
            </p>
            <?= snippet('components/breadcrumb', ['class' => 'mt-3']); ?>
        </div>
    </div>
</div>
