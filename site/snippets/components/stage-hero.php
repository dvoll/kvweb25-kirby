<?php

/**
 * @var \Kirby\Cms\Page $page
 */

use Kirby\Toolkit\A;

$sizes = [
    '100vw'
];

$logoSizes = [
    '(min-width: 32rem) 410px', // 448px
    '100vw'
];

$image = $image ?? null;
$logo = $logo ?? null;

/**
 * @var \Kirby\Content\Field|null $subline
 */
$subline = $subline ?? null;
/**
 * @var \Kirby\Content\Field|null $sublineLabel
 */
$sublineLabel = $sublineLabel ?? null;

?>
<div
    class="dvll-section dvll-section--real-top grid grid-stacked w-full relative overflow-clip rounded-b-2xl shadow-md min-h-[95svh] ">
    <?= snippet(
        'picture',
        [
            'image' => $image,
            'sizes' => A::join($sizes, ', '),
            'preset' => 'stageHero',
            'responsive' => true,
            'class' => 'absolute w-full h-full -z-1',
            'imgClass' => 'w-full h-full object-cover',
            'lazy' => false,
            'fetchpriority' => true
        ]
    ); ?>
    <div class="bg-[#352B2B] opacity-60"></div>
    <div class="grid grid-cols-1 grid-rows-[3fr_auto_2fr_auto] h-full z-1 text-baseline pt-30">
        <div class="row-start-2 py-4 px-6 lg:pr-6 justify-self-center flex flex-col items-center">
            <div class="w-full max-w-[410px]">
                <?= snippet(
                    'picture',
                    [
                        'image' => $logo,
                        'sizes' => A::join($logoSizes, ', '),
                        'preset' => 'campLogo',
                        'imgClass' => 'w-full h-auto',
                        'lazy' => false
                    ]
                ); ?>
            </div>
            <?php if ($subline->isNotEmpty()): ?>
                <div class="mt-10 md:mt-30 flex flex-col items-center text-center">
                    <?php if ($sublineLabel->isNotEmpty()): ?>
                        <div class="uppercase font-style font-bold text-base"><?= $sublineLabel->toHtml() ?></div>
                    <?php endif; ?>
                    <div class="font-style font-extrabold italic text-5xl md:text-6xl mr-3 md:mr-6"><?= $subline->toHtml() ?></div>
                </div>
            <?php endif; ?>
        </div>
        <div class="row-start-4 justify-self-center" x-data>
            <button
                class="btn btn--ghost flex flex-col text-white hover:text-contrast mb-2 cursor-pointer"
                @click="document.querySelector('#inhalt').scrollIntoView({ behavior: 'smooth', block: 'start' })">
                <span>Zu den Inhalten</span>
                <?= snippet(
                    'elements/icon',
                    [
                        'icon' => 'download',
                        'class' => 'size-12 py-2 pr-2',
                    ]
                )
                ?>
            </button>
        </div>
    </div>
</div>
