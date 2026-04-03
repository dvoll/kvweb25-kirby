<?php

/** Right sidebar: outline generated from heading blocks */

// find blocks field
$blocksField = null;
foreach (['blocks'] as $f) {
    if ($page->hasField($f)) {
        $blocksField = $f;
        break;
    }
}

$headings = [];
if ($blocksField) {
    foreach ($page->{$blocksField}()->toBlocks() as $block) {
        if ($block->type() === 'heading') {
            $data = $block->content()->toArray();
            $text = $data['text'] ?? '';
            $level = $data['level'] ?? 'h2';
            $num = intval(preg_replace('/[^0-9]/', '', $level)) ?: 2;
            if ($num >= 2 && $num <= 4) {
                $id = \Kirby\Toolkit\Str::slug($text);
                $headings[] = ['level' => $num, 'text' => $text, 'id' => $id];
            }
        }
    }
}

?>
<aside class="border-l border-gray-200 bg-white h-full">
    <nav class="p-6" aria-label="Inhaltsverzeichnis">
        <?php if (!empty($headings)): ?>
            <h3 class="heading-h4 mb-3">Inhalt</h3>
            <ul class="space-y-2 text-sm">
                <?php foreach ($headings as $h): ?>
                    <li x-data="scrollToId" class="pl-<?= ($h['level'] - 2) * 3 ?>">
                        <button class="dvll-link dvll-link--small"
                            @click="scrollTo('<?= $h['id'] ?>')"><?= htmlspecialchars($h['text']) ?></button>
                    </li>
                <?php endforeach ?>
            </ul>
        <?php endif ?>
    </nav>
</aside>
