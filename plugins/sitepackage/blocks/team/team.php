<?php

/** @var Kirby\Cms\Block $block */

use dvll\Sitepackage\Helpers\UuidSelectFieldHelper;

$contacts = UuidSelectFieldHelper::getCollectionForUuids(
    site()->contacts(), // sourceStructureField
    $block->contacts(), // entriesFieldWithUuids
    'name', // fieldToCheckNotEmpty
    true // selectionIsEntryField (since block->contacts is a uuid list)
);

$layout = $block->layout()->or('flex flex-wrap');
?>
<div class="dvll-block dvll-block--centered <?= $layout === 'grid' ? 'grid grid-cols-2 gap-6' : 'flex flex-wrap gap-6 justify-evenly' ?>">
    <?php foreach ($contacts as $contact): ?>
        <div class="flex flex-col items-center w-32 sm:w-40 overflow-clip">
            <?php /** @var \Kirby\Cms\File $image */ ?>
            <?php if ($contact->photo()->isNotEmpty() && $image = $contact->photo()->toFile()): ?>
                <img
                    class="rounded-full shrink-0 w-[75px] h-[75px] sm:w-[100px] sm:h-[100px]"
                    alt="Profilbild von <?= $contact->name()->escape() ?>"
                    src="<?= $image->thumb(['width' => 100, 'height' => 100, 'crop' => true])->url() ?>"
                    srcset="<?= $image->srcset('profilePictureBigger') ?>"
                    width="100"
                    height="100">
            <?php else: ?>
                <div class="bg-gray-200 rounded-full shrink-0 w-[75px] h-[75px] sm:w-[100px] sm:h-[100px]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-full h-full p-3 text-gray-400">
                        <circle cx="12" cy="8" r="4" />
                        <path d="M12 14c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z" />
                    </svg>
                </div>
            <?php endif ?>
            <h4 class="heading-h4 mt-2 text-center text-balance"><?= $contact->name() ?></h4>
            <?php if ($contact->subject()->isNotEmpty()): ?>
                <p class="typo italic text-center mb-1"><?= $contact->subject()->html() ?></p>
            <?php endif ?>
            <?php if ($contact->email()->isNotEmpty()): ?>
                <a class="inline gap-1 text-sm text-contrast py-0.5 text-center max-w-full overflow-hidden text-ellipsis text-nowrap" href="mailto:<?= Str::encode($contact->email()->html()) ?>" title="<?= Str::encode($contact->email()->html()) ?>"><?= snippet('elements/icon', ['icon' => 'email', 'class' => 'size-4 inline']) ?> <span class="dvll-link dvll-link--small "><?= Str::encode($contact->email()->html()) ?></span></a>
            <?php endif ?>
            <?php if ($contact->phone()->isNotEmpty()): ?>
                <a class="flex gap-1 text-sm text-contrast py-0.5 text-center" href="tel:<?= Str::encode($contact->phone()->html()) ?>"><?= snippet('elements/icon', ['icon' => 'mobilephone', 'class' => 'w-4 pt-1 shrink-0']) ?> <span class="dvll-link dvll-link--small text-wrap"><?= Str::encode($contact->phone()->html()) ?></span></a>
            <?php endif ?>
        </div>
    <?php endforeach; ?>
</div>
