<?php

/**
 * @var dvll\Sitepackage\TeaserBlogpostsBlock $block
 * @var \Kirby\Cms\Site $site
 * @var DefaultPage $page
 * @var \Kirby\Cms\Page $teaserPage
 */

$teaserPages = $block->myBlogposts()->limit(3);

?>

<div class="dvll-block dvll-block--narrow dvll-block--gap-sm">
    <div class="flex flex-row flex-wrap gap-4 md:gap-6">
        <h2 class="heading-lv2"><?= $block->content()->get('teaserTitle')->escape() ?></h2>
        <a <?= Html::attr([
                'href' => $site->find('blog')->url(),
                'class' => 'btn btn--secondary self-start',
            ]) ?>>Alle Beiträge anzeigen<?= snippet('elements/icon') ?></a>
    </div>
</div>
<div class="dvll-block <?php e($teaserPages->count() > 1, 'dvll-block--wide', 'dvll-block--narrow') ?>">
    <div class="grid grid-cols-(--dvll-card-grid-cols) gap-4 md:gap-6 <?php e($teaserPages->count() > 1, 'md:justify-center') ?>">
        <?php foreach ($teaserPages as $teaserPage): ?>
            <?= snippet('components/blogpost-card', [
                'title' => $teaserPage->title(),
                'text' => $block->showText()->toBool() ? $teaserPage->text()->excerpt(140) : null,
                'url' => $teaserPage->url(),
            ]) ?>
        <?php endforeach; ?>
    </div>
</div>
