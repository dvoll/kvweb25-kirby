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
                            <div class="h-[1px] my-1 bg-tertiary mx-4"></div>
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
            <ul class="flex flex-col flex-wrap gap-4 font-style text-sm text-contrast font-semibold mx-1">
                <button class="main-nav-close-button self-end btn btn--secondary btn--medium m-1 mt-4">Navigation schließen <?= snippet('elements/icon', ['icon' => 'cross']) ?></button>
                <?php foreach ($items as $item): ?>
                    <?php
                        $externalIcon = ($item['isExternal'] || $item['newTab']) ? snippet('elements/icon', ['icon' => 'external', 'class' => 'size-3 self-center'], true) : '';
                        $linkAttrs = [
                            'class' => 'nav-link px-4 flex gap-2 items-center',
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
                        </a>
                        <?php if ($item['hasSubMenu']): ?>
                            <?php $subMenu = $item['page']->children()->listed(); ?>
                            <ul class="mt-2 pl-4 flex flex-col gap-1">
                                <?php foreach ($subMenu as $subItem): ?>
                                    <li>
                                        <a class="nav-link" <?= attr([
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
    </dialog>
<?php endif ?>
