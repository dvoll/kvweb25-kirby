<?php

/** @var Kirby\Cms\Site $site */ ?>

<header class="fixed w-full top-0 z-20 pointer-events-none">
    <dvll-header class="max-w-[96rem] mx-auto dvll-container group">
        <div class="col-span-full flex flex-row gap-6 items-center justify-between py-4 md:py-6 px-4 md:px-6">
            <a href="<?= $site->url() ?>" class="flex items-center gap-2 pointer-events-auto" title="<?= $site->title()->escape() ?>">
                <img src="/assets/logos/logo.svg" alt="<?= $site->title()->escape() ?>" width="300" height="120" class="w-[8rem] md:w-[11.5rem] transition-transform origin-left md:origin-top-left group-[.scrolled]:scale-75" />
            </a>
            <div class="nav py-1 md:py-2 pl-2 md:pl-4 -mr-1 md:-mr-3 pointer-events-auto">
                <button aria-label="Hauptnavigation öffnen" title="Hauptnavigation öffnen" class="md:hidden main-nav-button btn btn--ghost btn--icon-left btn--medium"><?= snippet('elements/icon', ['icon' => 'hamburger', 'class' => 'size-4 scale-x-[1.2]']) ?> Navigation</button>
                <?= snippet('core/nav-items') ?>
            </div>
        </div>
    </dvll-header>
</header>
