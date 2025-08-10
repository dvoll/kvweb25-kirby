<?php

/** @var \Kirby\Content\Field $fact */

$elName = $elName ?? 'p';
$icon = $fact->icon()->isNotEmpty() ? $fact->icon() : 'fact';
?>

<<?= $elName ?> class="sm:px-2 py-3 pb-[0.6rem] sm:pr-4 sm:bg-offwhite rounded-lg flex items-center gap-2 min-w-32 max-w-[390px]">
    <?= snippet('elements/icon', ['icon' => $icon, 'class' => 'w-5 h-5 m-1 shrink-0 text-tertiary']) ?>
    <div class="flex flex-col gap-1 font-style text-contrast">
        <span class="text-sm text-gray-800 font-semibold leading-3"><?= $fact->name()->html() ?></span>
        <span class="font-body text-base leading-[1.125rem]"><?= $fact->value()->html() ?></span>
    </div>
</<?= $elName ?>>
