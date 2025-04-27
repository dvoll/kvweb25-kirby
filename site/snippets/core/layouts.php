<?php

/**
 * @var \Kirby\Cms\Page $page
 */

/**
 * @var \Kirby\Cms\Field $blockSections
 */
$blockSections = $page->content()->get('layouts');
?>

<?php foreach ($blockSections->toBlocks() as $blockSection): ?>
    <section class="dvll-section" id="<?= $blockSection->id() ?>">
        <?php $isTwoCol = $blockSection->col2() && $blockSection->col2()->isNotEmpty(); ?>
        <div class="dvll-section__layout <?php e($isTwoCol, 'dvll-section__layout--two-col') ?>">
            <?php if ($isTwoCol): ?>
                <div class="dvll-section__col">
                    <?= $blockSection->col1()->toBlocks() ?>
                </div>
                <div class="dvll-section__col">
                    <?= $blockSection->col2()->toBlocks() ?>
                </div>
            <?php else: ?>
                <?= $blockSection->col1()->toBlocks() ?>
            <?php endif; ?>
        </div>
    </section>
<?php endforeach ?>
