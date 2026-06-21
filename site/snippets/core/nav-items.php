<?php

/** @var Kirby\Cms\Site $site */

$mainNavigation = $site->content()->get('mainNavigation');
$items = [];

if ($mainNavigation->isNotEmpty()) {
    foreach ($mainNavigation->toStructure() as $item) {
        if ($item->label()->isEmpty() || $item->link()->isEmpty()) {
            continue;
        }

        $linkUrl = $item->link()->toUrl();
        $linkPage = $item->link()->toPage();

        if ($linkPage !== null && !$linkPage->isPublished()) {
            continue;
        }

        $items[] = [
            'label' => $item->label(),
            'url' => $linkUrl,
            'page' => $linkPage,
            'hasSubMenu' => $linkPage !== null && $linkPage->children()->listed()->isNotEmpty() && $item->label()->escape()->toString() !== 'Blog',
            'newTab' => $item->newTab()->toBool(),
            'isExternal' => $item->link()->linkType() === 'url',
        ];
    }
}

if (empty($items)) {
    foreach ($site->children()->listed() as $item) {
        if ($item->slug() === 'home') {
            continue;
        }

        $items[] = [
            'label' => $item->title(),
            'url' => $item->url(),
            'page' => $item,
            'hasSubMenu' => $item->children()->listed()->isNotEmpty() && $item->title()->escape()->toString() !== 'Blog',
            'newTab' => false,
            'isExternal' => false,
        ];
    }
}

if (count($items) > 0): ?>
    <nav class="desktop-nav hidden md:block pointer-events-auto">
        <ul class="flex flex-row flex-wrap gap-x-2 gap-y-1 font-style text-sm text-contrast font-semibold">
            <?php foreach ($items as $item): ?>
                <?php
                $externalIcon = ($item['isExternal'] || $item['newTab']) ? snippet('elements/icon', ['icon' => 'external', 'class' => 'size-4 self-center'], true) : '';
                $linkAttrs = [
                    'class' => 'nav-link px-4 flex gap-2 items-center group',
                    'href' => $item['url'],
                    'aria-current' => isset($item['page']) && $item['page']?->isOpen() ? 'page' : null,
                    'target' => $item['newTab'] ? '_blank' : null,
                    'rel' => $item['newTab'] ? 'noopener' : null,
                ];
                ?>
                <li class="relative">
                    <a <?= attr($linkAttrs) ?>>
                        <?= $item['label']->html() ?>
                        <?= $externalIcon ?>
                        <?php e($item['hasSubMenu'], snippet('elements/icon', ['icon' => 'chevron-down', 'class' => 'size-3 group-[.nav-link--open-submenu]:rotate-180'], true)) ?>
                    </a>
                    <?php if ($item['hasSubMenu']): ?>
                        <?php $subMenu = $item['page']->children()->listed(); ?>
                        <ul class="nav-submenu bg-gray-100 card none flex-col p-1">
                            <li>
                                <a class="nav-link py-3 rounded-md" <?= attr([
                                                                        'href' => $item['url'],
                                                                        'aria-current' => $item['page']->isOpen() ? 'page' : null,
                                                                    ]) ?>>
                                    <?= $item['label']->html() ?>
                                </a>
                            </li>
                            <div class="h-px my-1 bg-tertiary mx-4"></div>
                            <?php foreach ($subMenu as $subItem): ?>
                                <li>
                                    <a class="nav-link rounded-md" <?= attr([
                                                                        'href' => $subItem->url(),
                                                                        'aria-current' => $item['page']->isOpen() ? 'page' : null,
                                                                    ]) ?>>
                                        <?= $subItem->title() ?>
                                    </a>
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
            <ul class="flex flex-col flex-wrap gap-2 font-style text-sm text-contrast font-semibold mx-1">
                <button class="main-nav-close-button self-end btn btn--secondary btn--medium m-1 mt-4">Navigation schließen <?= snippet('elements/icon', ['icon' => 'cross']) ?></button>
                <?php foreach ($items as $index => $item): ?>
                    <?php
                    $externalIcon = ($item['isExternal'] || $item['newTab']) ? snippet('elements/icon', ['icon' => 'external', 'class' => 'size-3 self-center'], true) : '';
                    $linkAttrs = [
                        'class' => 'nav-link px-3 py-4 flex gap-2 items-center',
                        'href' => $item['url'],
                        'aria-current' => isset($item['page']) && $item['page']?->isOpen() ? 'page' : null,
                        'target' => $item['newTab'] ? '_blank' : null,
                        'rel' => $item['newTab'] ? 'noopener' : null,
                    ];
                    ?>
                    <li class="relative">
                        <?php if ($item['hasSubMenu']): ?>
                            <?php $subMenu = $item['page']->children()->listed(); ?>
                            <?php $parentLinkAttrs = [
                                'class' => 'nav-link py-4 rounded-md flex gap-2 items-center',
                                'href' => $item['url'],
                                'aria-current' => $item['page']->isOpen() ? 'page' : null,
                                'target' => $item['newTab'] ? '_blank' : null,
                                'rel' => $item['newTab'] ? 'noopener' : null,
                            ]; ?>
                            <button type="button" class="mobile-nav-toggle btn btn--ghost btn--medium flex items-center justify-between w-full text-left py-4" aria-expanded="false" aria-controls="mobile-nav-submenu-<?= $index ?>">
                                <span><?= $item['label']->html() ?></span>
                                <?= snippet('elements/icon', ['icon' => 'chevron-down', 'class' => 'size-4 mobile-nav-toggle-icon transition-transform duration-200'], true) ?>
                                <span class="sr-only">Untermenü für <?= $item['label']->escape() ?> umschalten</span>
                            </button>
                            <ul id="mobile-nav-submenu-<?= $index ?>" class="mobile-nav-submenu my-1 pl-4 hidden flex flex-col gap-1" aria-label="Untermenü für <?= $item['label']->escape() ?>">
                                <li>
                                    <a <?= attr($parentLinkAttrs) ?>>
                                        <?= $item['label']->html() ?>
                                        <?= $externalIcon ?>
                                    </a>
                                </li>
                                <div class="h-px my-1 bg-tertiary mx-4"></div>
                                <?php foreach ($subMenu as $subItem): ?>
                                    <li>
                                        <a class="nav-link py-4" <?= attr([
                                                                        'href' => $subItem->url(),
                                                                        'aria-current' => $item['page']->isOpen() ? 'page' : null
                                                                    ]) ?>>
                                            <?= $subItem->title() ?>
                                        </a>
                                    </li>
                                <?php endforeach ?>
                            </ul>
                        <?php else: ?>
                            <a <?= attr($linkAttrs) ?>>
                                <?= $item['label']->html() ?>
                                <?= $externalIcon ?>
                            </a>
                        <?php endif ?>
                    </li>
                <?php endforeach ?>
            </ul>
        </nav>
    </dialog>
<?php endif ?>
