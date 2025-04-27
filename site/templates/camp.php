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
        <div class="dvll-block dvll-block--narrow">
            <ul class="flex flex-wrap gap-6">
                <?php foreach ($page->facts()->toStructure() as $fact): ?>
                    <li class="fact">
                        <h3><?= snippet('elements/icon', ['icon' => $fact->icon(), 'class' => 'w-3 h-3 inline']) ?><?= $fact->name()->html() ?></h3>
                        <p><?= $fact->value()->html() ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>

        </div>
        <div class="dvll-block dvll-block--narrow">
            <?= $page->campIntro()->permalinksToUrls(); ?>
        </div>
    </div>
</section>
<?php snippet('core/layouts'); ?>
<?php endsnippet() ?>
