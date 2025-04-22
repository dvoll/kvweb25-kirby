<?php

/**
 * @var \Kirby\Cms\Block $block
 * @var \Kirby\Cms\Site $site
 * @var DefaultPage $page
 * @var \Kirby\Cms\Page $teaserPage
 */

$teaserPages = $site->find('blog')->children()->listed()->sortBy('date', 'desc')->limit(3);

?>

<div class="dvll-block dvll-block--wide">
    <div class="flex flex-row flex-wrap gap-4 md:gap-6 mb-6">
        <h2 class="heading-lv2"><?= $block->content()->get('teaserTitle')->escape() ?></h2>
        <a <?= Html::attr([
                'href' => $site->find('blog')->url(),
                'class' => 'btn btn--secondary self-start',
            ]) ?>>Alle Beiträge anzeigen<?= snippet('elements/icon') ?></a>
    </div>
    <div class="grid grid-cols-(--dvll-card-grid-cols) gap-4 md:gap-6">
        <?php foreach ($teaserPages as $teaserPage): ?>
            <?= snippet('components/blogpost-card', [
                'title' => $teaserPage->title(),
                'url' => $teaserPage->url(),
            ]) ?>
        <?php endforeach; ?>
    </div>
</div>
