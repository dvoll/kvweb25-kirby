<?php

/** @var Kirby\Cms\Site $site */

$footerPages = $site->children()->published();

?>

<footer class="dvll-container">
    <div class="dvll-section dvll-section--real-top mb-0">
        <div class="dvll-section__layout">
            <div class="dvll-block dvll-block--wide grid grid-cols-subgrid gap-y-4 mb-4 md:mb-12">
                <div class="self-end row-start-2 col-span-3 sm:row-start-auto sm:col-start-1 sm:col-span-1">
                    <a href="<?= $site->url() ?>" class="flex items-center gap-2 pointer-events-auto" title="<?= $site->title()->escape() ?>">
                        <img width="300" height="300" loading="lazy" src="/assets/logos/kv-buende25-logo_kreis-farbig.svg" alt="<?= $site->title()->escape() ?>" class="aspect-square w-full" />
                    </a>
                </div>
                <ul class="dvll-footer-list-layout row-start-1 col-span-full sm:col-start-3 sm:col-end-7 gap-y-4 gap-x-6 self-start justify-between mb-2" style="--footer-item-count: <?= $footerPages->count() ?>;">
                    <?php foreach ($footerPages as $footerPage): ?>
                        <li>
                            <a href="<?= $footerPage->url() ?>" class="btn btn--ghost">
                                <?= $footerPage->title()->html() ?><? snippet('elements/icon') ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="col-start-6 sm:col-start-8 col-end-10 self-center sm:self-end flex flex-col gap-4">
                    <!-- <ul class="flex gap-4">
                        <li>
                            <a href="#" class="flex items-center gap-2" title="Instagram des KV Bünde">
                                <?= snippet('elements/icon', ['icon' => 'fact', 'class' => 'size-4']) ?>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-2" title="Facebook des KV Bünde">
                                <?= snippet('elements/icon', ['icon' => 'fact', 'class' => 'size-4']) ?>
                            </a>
                        </li>
                    </ul> -->

                    <a href="<?= $site->url() ?>" class="flex items-center gap-2 pointer-events-auto" title="<?= $site->title()->escape() ?>">
                        <img width="300" height="120" loading="lazy" src="/assets/logos/logo.svg" alt="<?= $site->title()->escape() ?>" class="w-full" />
                    </a>
                </div>
            </div>
            <div class="dvll-block dvll-block--full mb-0 flex justify-end">
                <div class="bg-offwhite px-4 py-2 rounded-tl-lg">
                    <p class="font-style font-semibold text-gray-500 text-sm">Unterstützt von und gebaut mit <a class="dvll-link font-semibold inline-flex gap-1" href="https://getkirby.com/" target="_blank" rel="noopener" referrerpolicy="no-referrer">Kirby CMS<?= snippet('elements/icon', ['icon' => 'external', 'class' => 'size-3 self-center']) ?></a></p>
                </div>
            </div>
        </div>
    </div>

</footer>
