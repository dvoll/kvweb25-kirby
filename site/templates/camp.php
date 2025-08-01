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
        'logo' => $page->content()->get('heroLogo')->toFile(),
        'subline' => $page->content()->get('heroSubline'),
        'sublineLabel' => $page->content()->get('heroSublineLabel'),
    ]); ?>
<?php else: ?>
    <?php snippet('core/stage'); ?>
<?php endif; ?>
<section id="inhalt" class="dvll-section--small-gap">
    <div class="dvll-section__layout dvll-section__layout--two-col">
        <?php if ($page->myStageType() === null): ?>
            <div class="dvll-block dvll-block--narrow dvll-block--gap-sm">
                <h1 class="heading-title"><?= $page->myTitle() ?></h1>
                <?= snippet('components/breadcrumb', ['class' => 'mt-2']); ?>
            </div>
        <?php endif; ?>
        <div class="dvll-block dvll-block--narrow">
            <ul class="flex flex-wrap gap-6 py-4">
                <?php foreach ($page->facts()->toStructure() as $fact): ?>
                    <li class="fact">
                        <h3><?= snippet('elements/icon', ['icon' => $fact->icon(), 'class' => 'w-3 h-3 inline']) ?><?= $fact->name()->html() ?></h3>
                        <p><?= $fact->value()->html() ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>

        </div>
        <div class="dvll-block dvll-block--narrow">
            <div class="typo typo--rte"><?= $page->campIntro()->kirbytext()->permalinksToUrls(); ?></div>
        </div>
        <div class="dvll-block dvll-block--sidebar lg:row-start-1 lg:row-span-[30]">
            <div class="dvll-block">
                <?php snippet('components/contact', ['contacts' => $page->myContacts()]) ?>
            </div>
        </div>
    </div>
</section>
<section class="dvll-section">
    <div class="dvll-section__layout">
        <?php
        $links = $page->myLinksAndDownloads();
        if (count($links) > 0):
        ?>
            <div class="dvll-block dvll-block--wide">
                <h3 class="heading-lv3 mb-5">
                    <?= $page->getLinksAndDownloadsTitle() ?>
                </h3>
                <ul class="grid grid-cols-(--dvll-card-grid-cols--small) gap-3 w-full">
                    <?php foreach ($links as $link): ?>
                        <li class="">
                            <?= $link ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php snippet('core/layouts'); ?>
<?php endsnippet() ?>
