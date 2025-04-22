<?php

/** @var Kirby\Cms\Site $site */ ?>

<header class="sticky w-full top-0 overflow-x-clip z-20">
    <dvll-header class="lg:container mx-auto dvll-container group">
        <div class="col-span-full flex flex-row gap-6 items-center justify-between py-4 md:py-6 px-4 md:px-6">
            <a href="<?= $site->url() ?>" class="flex items-center gap-2" title="<?= $site->title()->escape() ?>">
                <img src=" /assets/logos/logo.svg" alt="<?= $site->title()->escape() ?>" class="w-[8rem] md:w-[11.5rem] transition-transform origin-left md:origin-top-left group-[.scrolled]:scale-75" />
            </a>
            <div class="nav py-2 pl-4 md:-mr-3">
                <button class="md:hidden main-nav-button btn btn--secondary">Menü öffnen</button>
                <?= snippet('core/nav-items') ?>
            </div>
        </div>
    </dvll-header>
</header>
