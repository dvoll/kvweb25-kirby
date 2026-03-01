<?php
/** Right sidebar: outline generated from heading blocks */

// find blocks field
$blocksField = null;
foreach (['col1','text','content','blocks'] as $f) {
  if ($page->hasField($f)) { $blocksField = $f; break; }
}

$headings = [];
if ($blocksField) {
  foreach ($page->{$blocksField}()->toBlocks() as $block) {
    if ($block->type() === 'heading') {
      $data = $block->content()->toArray();
      $text = $data['text'] ?? '';
      $level = $data['level'] ?? 'h2';
      $num = intval(preg_replace('/[^0-9]/','',$level)) ?: 2;
      if ($num >= 2 && $num <= 4) {
        $id = \Kirby\Toolkit\Str::slug($text);
        $headings[] = ['level' => $num, 'text' => $text, 'id' => $id];
      }
    }
  }
}

if (empty($headings)) return;
?>
<aside class="hidden lg:block fixed right-0 top-[var(--header-h)] h-screen w-[var(--right-w)] overflow-auto border-l bg-white z-20">
  <nav class="p-4" aria-label="Page outline">
    <h3 class="heading-h4 mb-3">On this page</h3>
    <ul class="space-y-2 text-sm">
      <?php foreach ($headings as $h): ?>
        <li class="pl-<?= ($h['level'] - 2) * 3 ?>">
          <a href="#<?= $h['id'] ?>" class="dvll-link--small"><?= htmlspecialchars($h['text']) ?></a>
        </li>
      <?php endforeach ?>
    </ul>
  </nav>
</aside>
