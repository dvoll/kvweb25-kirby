<?php

/** Left sidebar: nested tree for wiki pages */
/** @var Kirby\Cms\Page $page */

$root = $site->find('wiki') ?? $site->home();

function renderTree(\Kirby\Cms\Pages $pages, \Kirby\Cms\Page $current, int $level = 0, int $maxLevel = 2): void
{
    if ($pages->isEmpty() || $level > $maxLevel) return;

    $ulClass = match ($level) {
        0 => '',
        1 => 'pl-4 border-l border-gray-200',
        default => 'pl-3 border-l border-gray-100',
    };

    $liClass = match ($level) {
        0 => 'py-3',
        1 => 'py-2',
        default => 'py-0.5',
    };

    $linkClass = match ($level) {
        0 => 'dvll-link dvll-link--small bg-none',
        1 => 'dvll-link dvll-link--small text-sm',
        default => 'dvll-link dvll-link--small',
    };

    echo '<ul class="' . $ulClass . '">';
    foreach ($pages as $p) {
        $isActive = $p->id() === $current->id();
        $hasActiveChild = $current->parents()->has($p);
        $activeClass = $isActive ? ' is-active font-semibold bg-none' : ($hasActiveChild ? ' font-semibold' : '');
        echo '<li class="' . $liClass . '">';
        echo '<a href="' . $p->url() . '" class="' . $linkClass . $activeClass . '">' . htmlspecialchars($p->title()->value()) . '</a>';
        $children = $p->children()->listed();
        if ($children->count()) renderTree($children, $current, $level + 1, $maxLevel);
        echo '</li>';
    }
    echo '</ul>';
}

?>
<aside class="border-r border-gray-200 bg-white h-full">
    <nav class="p-6" aria-label="Wiki navigation">
        <a class="dvll-link inline-block mb-4 font-semibold" href="<?= $root->url() ?>"><?= htmlspecialchars($root->title()->value()) ?></a>
        <?php renderTree($root->children()->listed(), $page) ?>
    </nav>
</aside>
