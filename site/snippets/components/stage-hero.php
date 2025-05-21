<?php

/**
 * @var \Kirby\Cms\Page $page
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
];

$image = $image ?? '';

?>
<div class="dvll-section dvll-section--real-top grid grid-stacked w-full min-h-[95vh] relative">
    <?= snippet(
        'picture',
        [
            'image' => $image,
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
            <div class="md:grid grid-cols-1 grid-rows-[1fr_auto_1fr_auto] h-full">
                <div class="row-start-2 md:py-4 lg:pr-6 justify-self-center">
                    <img src="/assets/logos/kvweb25-design__zela-logo.png" alt="<?= $site->title()->escape() ?>" width="401" height="123" class="w-full max-w-[410px]" />
                    <div class="mt-8 flex flex-col items-center">
                        <div class="uppercase font-bold text-base">Sommer</div>
                        <div class="font-extrabold italic text-6xl">2025</div>
                    </div>
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
    </div>
</div>
