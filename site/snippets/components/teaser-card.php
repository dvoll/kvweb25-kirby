<?php

/**
 * @var \Kirby\Cms\Block $block
 *
 * @var \Kirby\Cms\File|null $image
 * @var string|null $title
 * @var string|null $text
 * @var string|null $url
 * @var string|null $buttonTitle
 * @var bool|null $dynamicContent - If true, content will be filled via Alpine.js
 */

use Kirby\Toolkit\A;
use Kirby\Toolkit\Html;

$dynamicContent = $dynamicContent ?? false;

$sizes = [
    '(min-width: 64.5rem) 275px', // 1032
    '(min-width: 57rem) 175px', // 912
    '(min-width: 49.5rem) 200px', // 792
    '(min-width: 28.125rem) 280px', // 450
    '200px'
]

?>
<div class="card card--with-hover relative @container">
    <?php if ($dynamicContent): ?>
        <a :href="eventData.tag.page.url"
           class="absolute inset-0 z-1"
           aria-hidden="true"
           tabindex="-1"
           :title="'Zur Seite: ' + eventData.tag.page.title"></a>
    <?php else: ?>
        <a <?= Html::attr([
                'href' => $url,
                'class' => 'absolute inset-0 z-1',
                'aria-hidden' => 'true',
                'tabindex' => '-1',
                'title' => 'Zur Seite: ' . ($buttonTitle ?? $title),
            ]) ?>></a>
    <?php endif; ?>

    <div class="card__layout h-full @min-card-lg:grid-cols-[1fr_1fr] @min-card-xl:grid-cols-[3fr_1fr]">
        <div class="area-main self-center py-5 pl-5 @min-card-md:py-6 @min-card-md:pl-6">
            <?php if ($dynamicContent): ?>
                <h3 class="heading-card mb-3" x-text="eventData.tag.page.teaserTitle"></h3>
                <p class="typo text-base line-clamp-3 md:line-clamp-none" x-text="eventData.tag.page.teaserText"></p>
                <a :href="eventData.tag.page.url" class="btn btn--secondary mt-6 md:mt-9 mr-2">
                    Zur Seite &#8222;<span x-text="eventData.tag.page.title"></span>&#8220;<?= snippet('elements/icon') ?>
                </a>
            <?php else: ?>
                <h3 class="heading-card mb-3"><?= $title ?></h3>
                <?php if (!empty($text)): ?>
                    <p class="typo text-base line-clamp-3 md:line-clamp-none">
                        <?= $text ?>
                    </p>
                <?php endif; ?>
                <a <?= Html::attr([
                        'href' => $url,
                        'class' => 'btn btn--secondary mt-6 md:mt-9 mr-2',
                    ]) ?>>Zur Seite &#8222;<?= $buttonTitle ?? $title ?>&#8220;<?= snippet('elements/icon') ?></a>
            <?php endif; ?>
        </div>

        <?php if ($dynamicContent): ?>
            <!-- Dynamic image handling for Alpine.js -->
            <template x-if="eventData.tag.page.teaserImage">
                <div class="area-image angled-cut origin-right @min-card-lg:origin-center">
                    <img :src="eventData.tag.page.teaserImage.url"
                         :srcset="eventData.tag.page.teaserImage.srcset"
                         :alt="eventData.tag.page.teaserImage.alt"
                         sizes="(min-width: 64.5rem) 275px, (min-width: 57rem) 175px, (min-width: 49.5rem) 200px, (min-width: 28.125rem) 280px, 200px"
                         class="w-full h-full object-cover"
                         loading="lazy"
                         width="400"
                         height="300">
                </div>
            </template>
            <template x-if="!eventData.tag.page.teaserImage">
                <div class="area-image angled-cut origin-right">
                    <div class="bg-offwhite w-full h-full flex flex-col items-center justify-center">
                        <?= snippet('elements/icon', [
                            'class' => 'text-tertiary size-12 ml-8 @min-card-lg:origin-center',
                        ]) ?>
                    </div>
                </div>
            </template>
        <?php else: ?>
            <!-- Static image handling for PHP -->
            <?php if (!empty($image)): ?>
                <?= snippet(
                    'picture',
                    [
                        'image' => $image,
                        'cropRatio' => 4 / 3,
                        'sizes' => A::join($sizes, ', '),
                        'preset' => 'teaser',
                        'class' => 'area-image angled-cut origin-right @min-card-lg:origin-center',
                        'imgClass' => 'w-full h-full',
                    ]
                ); ?>
            <?php else: ?>
                <div class="area-image angled-cut origin-right">
                    <div class="bg-offwhite w-full h-full flex flex-col items-center justify-center">
                        <?= snippet('elements/icon', [
                            'class' => 'text-tertiary size-12 ml-8 @min-card-lg:origin-center',
                        ]) ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
