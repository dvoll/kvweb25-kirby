<?php

/**
 * @var \Kirby\Cms\Page $page
 * @var \Kirby\Cms\Block $block
 */

use Kirby\Toolkit\A;

$sizes = [
    // TODO: check
    // '(min-width: 40rem) vw', // 640
    '(min-width: 96rem) 816px', // 1536
    '(min-width: 80rem) 674px', // 1280
    '(min-width: 64rem) 531px', // 1024
    '(min-width: 48rem) 389px', // 768
    '100vw'
]

?>
<div class="dvll-section dvll-section--real-top grid grid-stacked w-full min-h-[95vh] relative">
    <?= snippet(
        'picture',
        [
            'image' => $block->image()->toFile(),
            'cropRatio' => 3 / 2,
            'sizes' => A::join($sizes, ', '),
            'preset' => 'default',
            'responsive' => true,
            'class' => 'absolute w-full h-full -z-1',
        ]
    ); ?>
    <div class="bg-[#1D2021] opacity-60"></div>
    <div class="dvll-section__layout w-full pt-30 relative z-1 text-baseline">
        <div class="dvll-block dvll-block--wide ">
            <div class="md:grid grid-cols-1 grid-rows-[1fr_auto_2fr]">
                <div class="row-start-2 md:py-4 lg:pr-6">
                    <img src=" /assets/logos/logo.svg" alt="<?= $site->title()->escape() ?>" class="w-[8rem] h-[55.1px] md:w-[11.5rem] md:h-[73.4px] transition-transform origin-left md:origin-top-left group-[.scrolled]:scale-75" />
                    <p class="typo">
                        <?= $block->description()->kirbytext() ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
