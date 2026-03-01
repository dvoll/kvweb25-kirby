<?php

/**
 * @var Kirby\Cms\App $kirby
 * @var Kirby\Cms\Page $page
 * @var Kirby\Cms\Site $site
 */

$paginatedList = $page->children()->listed()->sortBy('date', 'desc')->paginate(9);
$pagination = $paginatedList->pagination();

snippet('base', slots: true); ?>

<?php snippet('core/stage'); ?>

<section class="dvll-section">
    <div class="dvll-section__layout">
        <div class="dvll-block dvll-block--wide grid grid-cols-(--dvll-card-grid-cols--small) gap-4 md:gap-6 md:justify-center auto-rows-fr">
            <?php foreach ($paginatedList as $post): ?>
                <?= snippet('components/blogpost-card', [
                    'title' => $post->title(),
                    'text' => $post->text()->excerpt(140),
                    'url' => $post->url(),
                    'date' => $post->date()->toDate('d.m.Y'),
                ]) ?>
            <?php endforeach ?>
        </div>
        <?php if ($pagination && $pagination->hasPages()): ?>
            <div class="dvll-block dvll-block--narrow">
                <?= snippet('components/pagination', [
                    'pagination' => $pagination,
                    'labelNext' => 'Ältere Beiträge',
                    'labelPrev' => 'Neuere Beiträge',
                ]) ?>
            </div>
        <?php endif ?>
    </div>
</section>
<?php endsnippet() ?>
