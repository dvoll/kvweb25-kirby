<?php

/** @var \Kirby\Cms\Block $block */
$caption = $block->caption();
$crop    = $block->crop()->isTrue();
$ratio   = $block->ratio()->or('auto');
$images  = $block->images()->toFiles();

$sizes = [
    '(min-width: 73.25rem) 1124px', // 1172
    '(min-width: 40rem) 100vw', // 640
    '140vw'
];
?>

<div class="dvll-block dvll-block--wide" x-data="gallery()">

    <div>
        <!-- Slider main container -->
        <div class="swiper relative" x-ref="swiperElement">
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper">
                <?php foreach ($images->values() as $key => $image): ?>
                    <div class="swiper-slide">
                        <?php $shouldBeCropped = $image->ratio() >= 1.3 && $image->ratio() <= 2.2; ?>
                        <?= snippet(
                            'picture',
                            [
                                'image' => $image,
                                'cropRatio' => $shouldBeCropped ?  16 / 9 : null,
                                'responsive' => true,
                                'preset' => 'default',
                                'imgClass' => 'w-full h-full object-cover ' . ($shouldBeCropped ? 'sm:object-cover' : 'sm:object-contain sm:!object-center'),
                                'class' => 'block aspect-square sm:aspect-video rounded-md bg-offwhite overflow-clip ' . (!$shouldBeCropped ? 'shadow-md shadow-offwhite-shadow/10' : 'shadow-lg shadow-offwhite-shadow/20'),
                                'sizes' => A::join($sizes, ', '),
                            ]
                        ); ?>
                        <div class="font-body text-sm text-contrast ml-4 mt-4 md:mt-4 mr-[9.625rem] pr-4 max-w-[40rem] min-h-[3rem]" :class="currentSlide === <?= $key ?> ? 'opacity-100' : 'opacity-0'">
                            <?= $image->caption()->kt() ?>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
            <div class="flex flex-wrap gap-4 justify-between absolute top-0 left-0 w-full z-10 pointer-events-none">
                <div class="aspect-square sm:aspect-video w-full"></div>
                <div class="pointer-events-auto gallery-control flex justify-end items-center gap-4 md:gap-6 ml-auto">
                    <div class="font-style text-base text-contrast pl-2">
                        <span x-text="currentSlide + 1"></span> / <span><?= $images->count() ?></span>
                    </div>
                    <div class="flex gap-1">
                        <button
                            class="btn btn--secondary p-4"
                            @click="prev()"
                            :disabled="isBeginning">
                            <?= snippet(
                                'elements/icon',
                                [
                                    'icon' => 'chevron-down',
                                    'class' => 'ml-0 size-4 rotate-90'
                                ]
                            ) ?>
                        </button>
                        <button
                            class="btn btn--secondary p-4"
                            @click="next()"
                            :disabled="isEnd">
                            <?= snippet(
                                'elements/icon',
                                [
                                    'icon' => 'chevron-down',
                                    'class' => 'ml-0 size-4 -rotate-90'
                                ]
                            ) ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
