<?php

/**
 * @var \Kirby\Cms\Block $block
 * @var DefaultPage $page
 */

$teaserPages = [];
$source = $block->content()->get('source')->value();
if ($source === 'selection') {
    /** @var \Kirby\Content\Field $pagesField */
    $pagesField = $block->content()->get('pages');
    $teaserPages = $pagesField->toPages();
} else {
    $teaserPages = $page->children()->listed();
}

?>

<div class="dvll-block dvll-block--wide">
    <div class="grid grid-cols-1 md:grid-cols-(--dvll-card-grid-cols) justify-center gap-4 md:gap-6">
        <?php /** @var dvll\Sitepackage\Models\CustomBasePage $teaserPage */ ?>
        <?php foreach ($teaserPages as $teaserPage): ?>
            <?= snippet('components/teaser-card', [
                'title' => $teaserPage->myTitle(),
                'buttonTitle' => $teaserPage->title(),
                'text' => Str::excerpt($teaserPage->myTeaserText(), 130),
                'image' => $teaserPage->myTeaserImage(),
                'url' => $teaserPage->url(),
            ]) ?>
        <?php endforeach; ?>
    </div>
</div>
