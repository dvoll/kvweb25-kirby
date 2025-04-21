<?php

/**
 * @var Kirby\Cms\App $kirby
 * @var Kirby\Cms\Page $page
 * @var Kirby\Cms\Site $site
 */

snippet('layout', slots: true); ?>
<section class="dvll-section">
    <div class="dvll-section__layout">
        <?= $page->content()->get('stage')->__call('toBlocks') ?>
    </div>
</section>
<?php foreach ($page->content()->get('layouts')->toLayouts() as $layout): ?>
    <section class="dvll-section" id="<?= $layout->id() ?>">
        <div class="dvll-section__layout">
            <?php foreach ($layout->columns() as $column): ?>
                <?= $column->blocks() ?>
            <?php endforeach ?>
        </div>
    </section>
<?php endforeach ?>
<?php endsnippet() ?>
