<?php

/**
 * @var Kirby\Cms\App $kirby
 * @var Kirby\Cms\Page $page
 * @var Kirby\Cms\Site $site
 */

snippet('layout', slots: true); ?>

<?php snippet('core/stage'); ?>

<section class="dvll-section">
    <div class="dvll-section__layout">
        <div class="dvll-block dvll-block--wide grid grid-cols-(--dvll-card-grid-cols--small) gap-4 md:gap-6 md:justify-center auto-rows-fr">
            <?php foreach ($page->children()->listed()->sortBy('date', 'desc') as $post): ?>
                <?= snippet('components/blogpost-card', [
                    'title' => $post->title(),
                    'text' => $post->text()->excerpt(140),
                    'url' => $post->url(),
                    'date' => $post->date()->toDate('d.m.Y'),
                ]) ?>
            <?php endforeach ?>
        </div>
    </div>
</section>
<?php endsnippet() ?>
