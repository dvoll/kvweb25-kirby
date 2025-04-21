<?php

/**
 * @var Kirby\Cms\App $kirby
 * @var Kirby\Cms\Page $page
 * @var Kirby\Cms\Site $site
 */

snippet('layout', slots: true); ?>
<section class="dvll-section">
    <?= $page->content()->get('stage')->__call('toBlocks') ?>
</section>
<?php foreach ($page->content()->get('layouts')->toLayouts() as $layout): ?>
    <section class="dvll-section" id="<?= $layout->id() ?>">
        <?php foreach ($layout->columns() as $column): ?>
            <?= $column->blocks() ?>
        <?php endforeach ?>
    </section>
<?php endforeach ?>
<?php endsnippet() ?>
