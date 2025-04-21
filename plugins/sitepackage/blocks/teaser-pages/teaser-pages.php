<?php

/**
 * @var \Kirby\Cms\Block $block
 * @var DefaultPage $page
 * @var \Kirby\Cms\Page $teaserPage
 */

$teaserPages = [];
$location = $block->content()->get('location')->value();
if ($location === 'selection') {
    $teaserPages = $block->content()->get('pages')->toPages();
} else {
    $teaserPages = $page->children()->listed();
}

?>

<div class="dvll-block col-span-full flex flex-col md:flex-wrap md:flex-row gap-y-4 md:gap-x-6 md:justify-center">
    <?php foreach ($teaserPages as $teaserPage): ?>
        <?= snippet('components/teaser-card', [
            'title' => $teaserPage->myTitle(),
            'text' => $teaserPage->myTeaserText(),
            'image' => $teaserPage->myTeaserImage(),
            'url' => $teaserPage->url(),
        ]) ?>
    <?php endforeach; ?>
</div>
