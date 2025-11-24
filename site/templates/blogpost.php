<?php

/**
 * @var BlogpostPage $page
 */


$sizes = [
    '(min-width: 80rem) 610px', // 1280
    '(min-width: 64rem) 860px', // 1024
    '(min-width: 48rem) 680px', // 768
    '100vw'
];

$contentImages = $page->content()->get('image')->toFiles();
$galleryImagesField = $page->content()->get('additionalImages');
$galleryImages = $galleryImagesField->toFiles();
// $contentImage = $contentImages->first();
// $caption = ($contentImage && $contentImage->caption()->isNotEmpty()) ? $contentImage->caption()->html() : null;

snippet('layout', slots: true); ?>
<section class="dvll-section">
    <div class="dvll-section__layout dvll-section__layout--two-col md:pt-6">
        <?php if (($blogPage = site()->find('blog')) && $blogPage->isNotEmpty() && $blogPage->isVisible()): ?>
            <div class="dvll-block dvll-block--narrow">
                <a href="<?= $blogPage->url() ?>" class="btn btn--secondary btn--icon-left"><?= snippet('elements/icon', ['icon' => 'arrow-left']) ?><span>Zur Übersichtsseite &#8222;Blog&#8220;</span></a>
            </div>
        <?php endif; ?>
        <div class="dvll-block dvll-block--narrow dvll-block--gap-sm">
            <div class="font-style font-semibold text-sm text-gray-500 mb-2">Beitrag - <?= $page->date()->toDate("d.m.Y") ?></div>
            <h1 class="heading-h2"><?= $page->myTitle() ?></h1>
        </div>
        <div class="dvll-block dvll-block--narrow">
            <div class=" typo typo--reading-size typo--rte">
                <?= $page->content()->get('text')->kirbytext()->permalinksToUrls(); ?>
            </div>
        </div>
        <?php if ($contentImages): ?>
            <?php foreach ($contentImages as $contentImage): ?>
                <?php $caption = ($contentImage && $contentImage->caption()->isNotEmpty()) ? $contentImage->caption()->html() : null; ?>
                <div class="dvll-block dvll-block--narrow">
                    <figure>
                        <?= snippet(
                            'picture',
                            [
                                'image' => $contentImage,
                                'sizes' => A::join($sizes, ', '),
                                'preset' => 'default',
                                'alt' => ($contentImage && $contentImage->alt()->isNotEmpty()) ? $contentImage->alt()->html() : null,
                            ]
                        ); ?>
                        <?php if (!empty($caption)) : ?>
                            <figcaption class="text-sm text-contrast mt-2 mx-4">
                                <?= $caption ?>
                            </figcaption>
                        <?php endif; ?>
                    </figure>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <div class="dvll-block dvll-block--sidebar lg:row-start-1 lg:row-span-[30]">
            <div class="dvll-block">

                <?php snippet('components/contact', ['contacts' => $page->myContacts()]) ?>
            </div>

            <?php
            $links = $page->myLinksAndDownloads();
            if (count($links) > 0):
            ?>
                <div class="dvll-block">
                    <h3 class="heading-h3 mb-5">
                        <?= $page->getLinksAndDownloadsTitle() ?>
                    </h3>
                    <ul class="flex flex-col gap-2">
                        <?php foreach ($links as $link): ?>
                            <li>
                                <?= $link ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php if($galleryImages): ?>
    <section class="dvll-section">
        <div class="dvll-section__layout">
            <?= snippet('components/gallery', ['images' => $galleryImagesField]) ?>
        </div>
    </section>
<?php endif; ?>
<?php if (($selectedTags = $page->selectedTags()) && $selectedTags->isNotEmpty()): ?>
    <section class="dvll-section">
        <div class="dvll-section__layout">
            <div class="dvll-block dvll-block--narrow">
                <p class="font-style font-semibold text-sm text-gray-500 mb-1">Schlagworte</p>
                <ul class="flex flex-wrap gap-2">
                    <?php foreach ($selectedTags as $tag): ?>
                        <li>
                            <?php if ($tag->page()->isNotEmpty() && ($tagSite = $tag->page()->toPage())) : ?>
                                <a href="<?= $tagSite->url() ?>" class="btn btn--secondary"><span><?= $tagSite->title() ?></span></a>
                            <?php else: ?>
                                <span class="block font-style text-sm text-contrast bg-secondary px-2 py-1 rounded-sm italic">
                                    <?= $tag->name()->escape() ?>
                                </span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php endsnippet() ?>
