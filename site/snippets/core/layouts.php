<?php

/**
 * @var dvll\Sitepackage\Models\CustomBasePage $page
 * @var array<string, mixed> $contacts
 */

/** @var \Kirby\Content\Field $blocks */
$blocks = $page->content()->get('blocks');

/** @var \Kirby\Content\Field $contacts */
$contacts = $page->myContacts() ;

$contactsDisplayOptions = $page->getContactsDisplayInLayoutOptions();
$showContact = $contactsDisplayOptions['show'] ?? false;
$showGeneralContact = $contactsDisplayOptions['showPartGeneral'] ?? false;
$contactNeighbourBlockCount = 3;
?>

<section class="dvll-section">
<?php if ($contactsDisplayOptions['show']): ?>
    <div class="dvll-section__layout dvll-section__layout--two-col">
        <div class="dvll-block dvll-block--sidebar lg:row-start-1 lg:row-span-[30]">
            <div class="dvll-block">
                <?php snippet('components/contact', compact('contacts', 'showGeneralContact')) ?>
            </div>
        </div>
<?php else: ?>
    <div class="dvll-section__layout">
<?php endif; ?>


        <?php foreach ($blocks->toBlocks() as $block) : ?>
            <?php
            // Render the block using the block model if available
            if ($block->type() === 'spacer' || ($contactsDisplayOptions['show'] && $contactNeighbourBlockCount === 0)): ?>
                <?php $contactNeighbourBlockCount = -1; ?>

    </div>
</section>
<section class="dvll-section">
    <div class="dvll-section__layout">
            <?php else: ?>
                <?php $contactNeighbourBlockCount--; ?>
            <?php endif; ?>
            <?= $block ?>
        <?php endforeach; ?>
    </div>
</section>
