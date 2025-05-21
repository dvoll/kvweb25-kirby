<?php

/**
 * @var \Kirby\Cms\Page $page
 */

use Kirby\Toolkit\A;

$sizes = [
    '100vw'
];

$logoSizes = [
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
    class="dvll-section dvll-section--real-top grid grid-stacked w-full relative"
    style="min-height: 95vh; min-height: 95svh;">
    <?= snippet(
        'picture',
        [
            'image' => $image,
            'sizes' => A::join($sizes, ', '),
            'preset' => 'stageHero',
            'responsive' => true,
            'class' => 'absolute w-full h-full -z-1',
        ]
    ); ?>
    <div class="bg-[#1D2021] opacity-60"></div>
    <div class="grid grid-cols-1 grid-rows-[3fr_auto_2fr_auto] h-full z-1 text-baseline pt-30">
        <div class="row-start-2 py-4 px-6 lg:pr-6 justify-self-center flex flex-col items-center">
            <div class="w-full max-w-[410px]">
                <?= snippet(
                    'picture',
                    [
                        'image' => $logo,
                        'sizes' => A::join($logoSizes, ', '),
                        'preset' => 'campLogo',
                        'imgClass' => 'h-auto',
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
        <div class="row-start-4 justify-self-center">
            <button
                class="btn btn--ghost flex flex-col text-white hover:text-contrast mb-2 cursor-pointer"
                onclick="document.querySelector('#inhalt').scrollIntoView({ behavior: 'smooth', block: 'start' })">
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
