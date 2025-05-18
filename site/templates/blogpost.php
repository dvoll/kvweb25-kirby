<?php

/**
 * @var BlogpostPage $page
 */


$sizes = [
    // '(min-width: 40rem) vw', // 640
    '(min-width: 96rem) 816px', // 1536
    '(min-width: 80rem) 674px', // 1280
    '(min-width: 64rem) 531px', // 1024
    '(min-width: 48rem) 389px', // 768
    '100vw'
];

$contentImage = $page->getContentImage();
$caption = ($contentImage && $contentImage->caption()->isNotEmpty()) ? $contentImage->caption()->html() : null;

snippet('layout', slots: true); ?>
<section class="dvll-section">
    <div class="dvll-section__layout dvll-section__layout--two-col">
        <?php if (($blogPage = site()->find('blog')) && $blogPage->isNotEmpty() && $blogPage->isVisible()): ?>
            <div class="dvll-block dvll-block--narrow">
                <a href="<?= $blogPage->url() ?>" class="btn btn--secondary btn--icon-left"><?= snippet('elements/icon', ['icon' => 'arrow-left']) ?><span>Zur Übersichtsseite &#8222;Blog&#8220;</span></a>
            </div>
        <?php endif; ?>
        <div class="dvll-block dvll-block--narrow dvll-block--gap-sm">
            <div class="font-style font-semibold text-sm text-gray-500 mb-2">Beitrag - <?= $page->date()->toDate("d.m.Y") ?></div>
            <h1 class="heading-lv2"><?= $page->myTitle() ?></h1>
        </div>
        <div class="dvll-block dvll-block--narrow">
            <div class="typo typo--rte">
                <?= $page->content()->get('text')->kirbytext()->permalinksToUrls(); ?>
            </div>
        </div>
        <?php if ($contentImage): ?>
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
        <?php endif; ?>
        <div class="dvll-block col-span-full lg:col-start-7 lg:col-span-3 lg:row-start-1 lg:row-span-[30]">
            <div class="dvll-block">

                <?php snippet('components/contact', ['contacts' => $page->myContacts()]) ?>
            </div>

            <?php
            $downloads = $page->myDownloads();
            $links = $page->myLinks();
            if ((count($downloads) > 0) || (count($links) > 0)):
            ?>
                <div class="dvll-block">
                    <h3 class="heading-lv3 mb-5">
                        <?php
                        if (count($links) > 0 && count($downloads) > 0) {
                            echo 'Verlinkungen und Downloads';
                        } elseif (count($downloads) > 0) {
                            echo 'Downloads';
                        } elseif (count($links) > 0) {
                            echo 'Verlinkungen';
                        }
                        ?>
                    </h3>
                    <ul class="flex flex-wrap gap-2 w-full">
                        <?php foreach ($links as $link): ?>
                            <li class="w-full">
                                <?= $link ?>
                            </li>
                        <?php endforeach; ?>
                        <?php foreach ($downloads as $file): ?>
                            <li class="w-full">
                                <?= snippet(
                                    'components/asset-card',
                                    [
                                        'assetFile' => $file,
                                        'class' => '',
                                    ]
                                ); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<section class="dvll-section">
    <div class="dvll-section__layout">
        <div class="dvll-block dvll-block--narrow">
            <p class="font-style font-semibold text-sm text-gray-500 mb-1">Schlagworte</p>
            <ul class="flex flex-wrap gap-2">
                <?php foreach ($tags = $page->tags()->split(',') as $tag): ?>
                    <li><a href="<?= site()->find($tag)->url() ?>" class="btn btn--secondary"><span><?= site()->find($tag)->title() ?></span></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</section>
<?php snippet('core/layouts'); ?>
<?php endsnippet() ?>
