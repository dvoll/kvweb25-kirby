<?php

/**
 * @var CampPage $page
 */

/** @var Kirby\Content\Field $stageField */
$stageField = $page->content()->get('stage');

snippet('layout', slots: true); ?>
<?php if ($page->myStageType() === null): ?>
    <?php snippet('components/stage-hero', [
        'image' => $page->content()->get('heroImage')->toFile(),
    ]); ?>
<?php else: ?>
    <?php snippet('core/stage'); ?>
<?php endif; ?>
<section class="dvll-section">
    <div class="dvll-section__layout dvll-section__layout--two-col">
        <?php if ($page->myStageType() === null): ?>
            <div class="dvll-block dvll-block--narrow dvll-block--gap-sm">
                <h1 class="heading-title"><?= $page->myTitle() ?></h1>
                <?= snippet('components/breadcrumb', ['class' => '']); ?>
            </div>
            <div class="dvll-block dvll-block--narrow">
            </div>
        <?php endif; ?>
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
        <?php snippet('components/contact', ['contacts' => $page->myContacts()]) ?>
    </div>
</section>
<?php snippet('core/layouts'); ?>
<?php endsnippet() ?>
