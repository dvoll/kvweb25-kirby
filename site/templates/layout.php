<?php

/**
 * @var Kirby\Cms\App $kirby
 * @var Kirby\Cms\Page $page
 * @var Kirby\Cms\Site $site
 */

snippet('base', slots: true); ?>
    <?php snippet('core/stage'); ?>
    <?php snippet('core/layouts'); ?>
<?php endsnippet() ?>
