<?php

/** @var Kirby\Cms\Site $site */ ?>

<header class="md:sticky top-0">
    <dvll-header class="lg:container mx-auto dvll-container">
        <div class="col-span-full flex flex-row gap-6 items-center justify-between py-6">
            <a href="<?= $site->url() ?>" class="flex items-center gap-2" title="<?= $site->title()->escape() ?>">
                <img src=" /assets/logos/logo.svg" alt="<?= $site->title()->escape() ?>" class="w-[11.5rem]" />
            </a>
            <div class="nav py-2 pl-4 md:-mr-3">
                <button class="md:hidden main-nav-button btn btn--secondary">Menü öffnen</button>
                <?= snippet('core/nav-items') ?>
            </div>
        </div>
    </dvll-header>
</header>
