<?php

/**
 * @var Kirby\Cms\App $kirby
 * @var Kirby\Cms\Page $page
 * @var Kirby\Cms\Site $site
 */

snippet('layout', slots: true); ?>
    <section class="dvll-section">
        <div class="dvll-section__layout">
            <?php snippet('components/stage-welcome'); ?>
        </div>
    </section>
    <?php snippet('core/layouts'); ?>
<?php endsnippet() ?>
