<?php

/**
 * @var \Kirby\Cms\Page $page
 * @var array<string, mixed> $contacts
 */

use dvll\Sitepackage\Models\LayoutWithContactBlock;

/**
 * @var \Kirby\Content\Field $blockSections
 */
$blockSections = $page->content()->get('layouts');
?>

<?php foreach ($blockSections->toBlocks() as $blockSection): ?>
    <section class="dvll-section" id="<?= $blockSection->id() ?>">
        <?php $isTwoCol = ($blockSection->col2() && $blockSection->col2()->isNotEmpty()); ?>
        <div class="dvll-section__layout <?php e($blockSection instanceof LayoutWithContactBlock || $isTwoCol, 'dvll-section__layout--two-col') ?>">
            <?php
                /** @var \Kirby\Content\Field $col1 */
                $col1 = $blockSection->content()->get('col1');
            ?>
            <?= $col1->toBlocks() ?>
            <?php if ($isTwoCol): ?>
                <!-- TODO -->
            <?php elseif ($blockSection instanceof LayoutWithContactBlock): ?>
                <?php if (is_array($contacts = $blockSection->myContacts())): ?>
                    <?php snippet('components/contact', compact('contacts')) ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>
<?php endforeach ?>
