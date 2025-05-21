<?php

/**
 * @var \Kirby\Content\Field $contact
 * @var \Kirby\Cms\File $image
 * @var \Kirby\Cms\User $user
 */

 // Toggle to be used in future
$showGeneralContact = true;

// TODO: Remove column information from snippet
?>
<div class="rounded-md bg-offwhite pl-6 pr-4 py-8 self-start flex flex-col gap-8">
    <div class="flex flex-col gap-6">
        <h2 class="heading-lv2">Noch Fragen?<br>
            Melde dich bei uns!</h2>
        <?php if ($showGeneralContact): ?>
            <div class="">
                <p class="mb-4">Bei allgemeinen Fragen nutze gerne die Kontaktmöglichkeiten auf folgender Seite:</p>
                <a class="btn btn--secondary" href="/kontakt">Zur Kontaktseite<?= snippet('elements/icon') ?></a>
            </div>
        <?php endif ?>
    </div>
    <?php if (!empty($contacts) && is_array($contacts) && count($contacts) > 0): ?>
        <div class="flex flex-col gap-6">
            <?php foreach ($contacts as $contactEntry): ?>
                <?php if ($contactEntry === null) continue; ?>
                <?php $contact = $contactEntry->contact()->toObject(); ?>
                <div class="flex gap-4 items-start">
                    <?php /** @var \Kirby\Cms\File $image */ ?>
                    <?php if ($contact->photo() && $image = $contact->photo()->toFile()): ?>
                        <img
                            class="rounded-full"
                            alt="Profilbild von <?= $contact->name()->escape() ?>"
                            src="<?= $image->thumb(['width' => 75, 'height' => 75, 'crop' => true])->url() ?>"
                            srcset="<?= $image->srcset('profilePicture') ?>"
                            width="75"
                            height="75">
                    <?php else: ?>
                        <div class="bg-gray-100 rounded-full shrink-0" style="width: 75px; height: 75px">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-full h-full p-3 text-gray-400">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M12 14c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z" />
                            </svg>
                        </div>
                    <?php endif ?>
                    <div class="flex flex-col">
                        <h4 class="heading-lv4 mt-2"><?= $contact->name() ?></h4>
                        <?php if ($contact->subject()->isNotEmpty()): ?>
                            <p class="typo italic mb-1"><?= $contact->subject()->html() ?></p>
                        <?php endif ?>
                        <?php if ($contact->email()->isNotEmpty()): ?>
                            <p class="typo"><a class="flex gap-1 text-sm" href="mailto:<?= $contact->email()->html() ?>"><?= snippet('elements/icon', ['icon' => 'email', 'class' => 'w-4 pt-1']) ?> <?= $contact->email()->html() ?></a></p>
                        <?php endif ?>
                        <?php if ($contact->phone()->isNotEmpty()): ?>
                            <p class="typo"><a class="flex gap-1" href="tel:<?= $contact->phone()->html() ?>"><?= snippet('elements/icon', ['icon' => 'mobilephone', 'class' => 'w-4 pt-1']) ?> <?= $contact->phone()->html() ?></a></p>
                        <?php endif ?>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    <?php endif; ?>
</div>
