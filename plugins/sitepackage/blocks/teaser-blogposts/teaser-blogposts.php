<?php

/**
 * @var dvll\Sitepackage\Models\TeaserBlogpostsBlock $block
 * @var \Kirby\Cms\Site $site
 * @var DefaultPage $page
 * @var \Kirby\Cms\Page $teaserPage
 */

$teaserPages = $block->myBlogposts()->limit(3);

?>

<div class="dvll-block dvll-block--wide dvll-block--gap-sm">
    <div class="grid grid-cols-1 md:grid-cols-(--dvll-card-grid-cols--small) gap-4 md:gap-6 md:justify-center">
        <?php foreach ($teaserPages as $teaserPage): ?>
            <?= snippet('components/blogpost-card', [
                'title' => $teaserPage->title(),
                'text' => $block->showText()->toBool() ? $teaserPage->text()->excerpt(140) : null,
                'url' => $teaserPage->url(),
            ]) ?>
        <?php endforeach; ?>
        <?php if ($teaserPages->count() === 0): ?>
            <?= snippet('components/blogpost-card', [
                'title' => 'Hier gibt es gerade nichts Passendes',
                'text' => 'Schau doch mal auf der Seite mit allen Blogbeiträgen vorbei',
                'url' => '/blog',
                'teaser' => true,
                'buttonText' => 'Zur Blog Seite'
            ]) ?>
        <?php endif; ?>
        <?php if ($teaserPages->count() === 1 || $teaserPages->count() === 2): ?>
            <?= snippet('components/blogpost-card', [
                'title' => 'Sieh dir alle Beiträge an',
                'text' => 'Gehe zur Seite mit allen Blogbeiträgen',
                'url' => '/blog',
                'teaser' => true,
                'buttonText' => 'Zur Blog Seite'
            ]) ?>
        <?php endif; ?>
    </div>
</div>

<?php if ($teaserPages->count() >= 3): ?>
<div class="dvll-block dvll-block--narrow">
    <div class="flex flex-row flex-wrap justify-end">
        <a <?= Html::attr([
                'href' => $site->find('blog')->url(),
                'class' => 'btn btn--secondary self-start',
            ]) ?>>Alle Beiträge anzeigen<?= snippet('elements/icon') ?></a>
    </div>
</div>
<?php endif; ?>
