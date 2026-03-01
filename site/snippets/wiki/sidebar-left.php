<?php
/** Left sidebar: nested tree for wiki pages */
/** @var Kirby\Cms\Page $page */

$root = null;
foreach ($page->parents() as $p) {
  if ($p->template() === 'wiki') { $root = $p; break; }
}
if (!$root && $page->template() === 'wiki') $root = $page;
if (!$root) $root = $site->find('wiki') ?? $site->home();

function renderTree(\Kirby\Cms\Pages $pages, \Kirby\Cms\Page $current): void {
  if ($pages->isEmpty()) return;
  echo '<ul class="pl-4">';
  foreach ($pages as $p) {
    $active = ($p->id() === $current->id()) ? ' is-active' : '';
    echo '<li class="py-1">';
    echo '<a href="' . $p->url() . '" class="block text-sm' . $active . '">' . htmlspecialchars($p->title()->value()) . '</a>';
    $children = $p->children()->listed();
    if ($children->count()) renderTree($children, $current);
    echo '</li>';
  }
  echo '</ul>';
}

?>
<aside class="hidden md:block fixed left-0 top-[var(--header-h)] h-screen w-[var(--left-w)] overflow-auto border-r bg-white z-20">
  <nav class="p-4" aria-label="Wiki navigation">
    <h2 class="heading-h4 mb-3"><?= htmlspecialchars($root->title()->value()) ?></h2>
    <?php renderTree($root->children()->listed()->filter(fn($p)=> $p->listed()), $page) ?>
  </nav>
</aside>
