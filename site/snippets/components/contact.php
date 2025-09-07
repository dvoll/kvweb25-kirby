<?php

/**
 * @var \Kirby\Content\Field $contact
 * @var \Kirby\Cms\Collection<\Kirby\Content\Field>|null $contacts
 * @var \Kirby\Cms\File $image
 * @var \Kirby\Cms\User $user
 */

$showGeneralContact = $showGeneralContact ?? true;

?>
<div class="rounded-md bg-offwhite pl-5 md:pl-6 pr-4 pb-6 md:pb-8 pt-5 md:pt-6 self-start flex flex-col gap-8">
    <div class="flex flex-col gap-4">
        <h2 class="heading-h2">Noch Fragen?<br>
            Melde dich bei uns!</h2>
        <?php if ($showGeneralContact): ?>
            <div class="">
                <p class="mb-4">Bei allgemeinen Fragen nutze gerne die Kontaktmöglichkeiten auf folgender Seite:</p>
                <a class="btn btn--secondary" href="/kontakt">Zur Kontaktseite<?= snippet('elements/icon') ?></a>
            </div>
        <?php endif ?>
    </div>
    <?php if ($contacts && $contacts->isNotEmpty()): ?>
        <div class="flex flex-col gap-6">
            <?php foreach ($contacts as $contact): ?>
                <div class="flex gap-4 items-start">
                    <?php /** @var \Kirby\Cms\File $image */ ?>
                    <?php if ($contact->photo()->isNotEmpty() && $image = $contact->photo()->toFile()): ?>
                        <img
                            class="rounded-full shrink-0"
                            alt="Profilbild von <?= $contact->name()->escape() ?>"
                            src="<?= $image->thumb(['width' => 75, 'height' => 75, 'crop' => true])->url() ?>"
                            srcset="<?= $image->srcset('profilePicture') ?>"
                            width="75"
                            height="75">
                    <?php else: ?>
                        <div class="bg-gray-100 rounded-full shrink-0 w-[75px] h-[75px]">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-full h-full p-3 text-gray-400">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M12 14c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z" />
                            </svg>
                        </div>
                    <?php endif ?>
                    <div class="flex flex-col">
                        <h4 class="heading-h4 mt-2 text-balance"><?= $contact->name() ?></h4>
                        <?php if ($contact->subject()->isNotEmpty()): ?>
                            <p class="typo italic mb-1"><?= $contact->subject()->html() ?></p>
                        <?php endif ?>
                        <?php if ($contact->email()->isNotEmpty()): ?>
                            <a class="flex gap-1 text-sm text-contrast py-0.5" href="mailto:<?= Str::encode($contact->email()->html()) ?>"><?= snippet('elements/icon', ['icon' => 'email', 'class' => 'w-4 pt-1']) ?> <span class="dvll-link dvll-link--small overflow-hidden text-ellipsis text-nowrap"><?= Str::encode($contact->email()->html()) ?></span></a>
                        <?php endif ?>
                        <?php if ($contact->phone()->isNotEmpty()): ?>
                            <a class="flex gap-1 text-sm text-contrast py-0.5" href="tel:<?= Str::encode($contact->phone()->html()) ?>"><?= snippet('elements/icon', ['icon' => 'mobilephone', 'class' => 'w-4 pt-1']) ?> <span class="dvll-link dvll-link--small overflow-hidden text-ellipsis text-nowrap"><?= Str::encode($contact->phone()->html()) ?></span></a>
                        <?php endif ?>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    <?php endif; ?>
</div>
