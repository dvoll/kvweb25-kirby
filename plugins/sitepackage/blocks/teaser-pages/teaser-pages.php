<?php

/**
 * @var \Kirby\Cms\Block $block
 * @var DefaultPage $page
 * @var \Kirby\Cms\Page $teaserPage
 */

$teaserPages = [];
$source = $block->content()->get('source')->value();
if ($source === 'selection') {
    $teaserPages = $block->content()->get('pages')->toPages();
} else {
    $teaserPages = $page->children()->listed();
}

?>

<div class="dvll-block dvll-block--wide">
    <div class="grid grid-cols-1 md:grid-cols-(--dvll-card-grid-cols) justify-center gap-4 md:gap-6">
        <?php foreach ($teaserPages as $teaserPage): ?>
            <?= snippet('components/teaser-card', [
                'title' => $teaserPage->myTitle(),
                'buttonTitle' => $teaserPage->title(),
                'text' => $teaserPage->myTeaserText(),
                'image' => $teaserPage->myTeaserImage(),
                'url' => $teaserPage->url(),
            ]) ?>
        <?php endforeach; ?>
    </div>
</div>
