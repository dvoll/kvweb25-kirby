<?php

/** @var Kirby\Cms\Pages $pages */

$items = $pages->listed();
if ($items->isNotEmpty()): ?>
    <nav class="desktop-nav hidden md:block">
        <ul class="flex flex-row flex-wrap gap-x-4 gap-y-1 font-style text-sm text-contrast font-semibold">
            <?php foreach ($items as $item): ?>
                <?php $subMenu = $item->children(); ?>
                <?php $hasSubMenu = $subMenu->isNotEmpty() && $item->title()->escape()->toString() !== 'Blog'; ?>
                <li class="relative"><a class="nav-link px-4 flex gap-2 items-center group" <?php e($item->isOpen(), 'aria-current="page"') ?> href="<?= $item->url() ?>"><?= $item->title()->html() ?><?php e($hasSubMenu, snippet('elements/icon', ['icon' => 'chevron-down', 'class' => 'w-3 h-3 group-[.nav-link--open-submenu]:rotate-180'], return: true)) ?></a>
                    <?php if ($hasSubMenu): ?>
                        <ul class="nav-submenu bg-gray-100 card shadow-sm none flex-col">
                            <li><a class="nav-link" <?php e($item->isOpen(), 'aria-current="page"') ?> href="<?= $item->url() ?>"><?= $item->title()->html() ?></a>
                            </li>
                            <div class="h-[1px] bg-tertiary mx-4"></div>
                            <?php foreach ($subMenu as $subItem): ?>
                                <li>
                                    <a
                                        class="nav-link"
                                        <?php e($item->isOpen(), 'aria-current="page"') ?>
                                        href="<?= $subItem->url() ?>"><?= $subItem->title() ?></a>
                                </li>
                            <?php endforeach ?>
                        </ul>
                    <?php endif ?>
                </li>
            <?php endforeach ?>
        </ul>
    </nav>
    <dialog id="mobile-nav-modal" class="any-modal">
        <nav class="mobile-nav">
            <ul class="flex flex-col flex-wrap gap-4 font-style text-sm text-contrast font-semibold mx-1">
                <button class="main-nav-close-button block btn btn--secondary py-4 m-1">Menü schließen</button>
                <?php foreach ($items as $item): ?>
                    <li class="relative"><a class="nav-link px-4" <?php e($item->isOpen(), 'aria-current="page"') ?> href="<?= $item->url() ?>"><?= $item->title()->html() ?></a>
                        <?php $subMenu = $item->children(); ?>
                        <?php $hasSubMenu = $subMenu->isNotEmpty() && $item->title()->escape()->toString() !== 'Blog'; ?>
                        <?php if ($hasSubMenu): ?>
                            <ul class="mt-2 pl-4 flex flex-col gap-1">
                                <?php foreach ($subMenu as $subItem): ?>
                                    <li>
                                        <a
                                            class="nav-link"
                                            <?php e($item->isOpen(), 'aria-current="page"') ?>
                                            href="<?= $subItem->url() ?>"><?= $subItem->title() ?></a>
                                    </li>
                                <?php endforeach ?>
                            </ul>
                        <?php endif ?>
                    </li>
                <?php endforeach ?>
            </ul>
        </nav>
    </dialog>
<?php endif ?>
