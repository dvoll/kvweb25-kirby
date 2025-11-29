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
            <div class="dvll-block dvll-block--narrow">
                <h1 class="heading-title"><?= $page->myTitle() ?></h1>
                <?= snippet('components/breadcrumb', ['class' => 'mt-2']); ?>
            </div>
        <?php endif; ?>
        <div class="dvll-block dvll-block--wide">
            <ul class="facts-list">
                <?php foreach ($page->facts()->toStructure() as $fact): ?>
                    <?= snippet('components/fact', [
                        'fact' => $fact,
                        'elName' => 'li'
                    ]) ?>
                <?php endforeach; ?>
            </ul>

        </div>
        <div class="dvll-block dvll-block--narrow">
            <div class=" typo typo--reading-size typo--rte"><?= $page->campIntro()->kirbytext()->permalinksToUrls(); ?></div>
        </div>
        <div class="dvll-block dvll-block--sidebar lg:row-start-1 lg:row-span-[30]">
            <div class="dvll-block">
                <?php snippet('components/contact', ['contacts' => $page->myContacts()]) ?>
            </div>
        </div>
    </div>
</section>
<?php
    $links = $page->myLinksAndDownloads();
    if (count($links) > 0):
?>
<section class="dvll-section">
    <div class="dvll-section__layout">
            <div class="dvll-block dvll-block--wide">
                <h3 class="heading-h3 mb-5">
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
        </div>
    </section>
<?php endif; ?>
<?php snippet('core/layouts'); ?>
<?php endsnippet() ?>
