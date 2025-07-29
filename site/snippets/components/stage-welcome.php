<?php

/**
 * @var HomePage $page
 */

/** @var \Kirby\Content\Field $imagesField */
$imagesField = $page->content()->get('welcomeStageImages');

$images = $imagesField->toFiles();

$positions = [
    [2, 0],
    [3, 0],
    [4, 0],
    [1, 1],
    [2, 1],
    [3, 1],
    [4, 1],
    [0, 2],
    [1, 2],
    [2, 2],
    [3, 2],
    [1, 3],
    [2, 3],
];

$positionsShadow = [
    [3, 0],
    [2, 0],
    [1, 0],
    [0, 0],
    [3, 1],
    [2, 1],
    [1, 1],
    [4, 1],
    [3, 2],
    [2, 2],
    [1, 2],
    [3, 3],
    [2, 3],
];

$imageStartDelay = 2.2;
$baseDelay = 0.25; // seconds
$xDelay = 0.06; // seconds per column
$decay = 0.65;
$delay = 0;

?>
<div
    class="dvll-block dvll-block--wide flex gap-4">
    <div class="stage-welcome grid grid-cols-[auto_repeat(5,_1fr)] sm:grid-cols-[minmax(0,_calc(10%_+_50vw_-_25vh))_repeat(5,_1fr)] lg:grid-cols-[auto_repeat(5,_1fr)] grid-rows-[1fr_repeat(4,_auto)] lg:grid-rows-[1fr_repeat(4,_auto)] gap-1.5 md:gap-2 lg:gap-3 grow">
        <div class="stage-welcome__text-box col-start-1 -col-end-1 lg:col-start-1 lg:col-end-2 row-start-1 row-end-2 lg:row-start-1 lg:row-end-4 sm:-mb-8 md:-mb-20 lg:-mb-0 lg:-mr-20 text-4xl sm:text-[3.25rem] leading-10 sm:leading-14 pr-8 relative self-end py-8">
            <div class="flex flex-col max-w-min">
                <div class="flex items-end gap-4">
                    <span class="transition-drive transition-drive--to-right" style="--transition-delay: 0.6s;">Jugend.</span>
                    <span class="size-10 transition-drive transition-drive--to-right" style="--transition-delay: .62s;">
                        <svg width="100%" height="100%" viewBox="0 0 50 50" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
                            <rect id="title-arrow1-box" x="0" y="0" width="50" height="50" style="fill:none;" />
                            <path d="M33.013,33.953c0,-2.307 -0.096,-6.061 -0.628,-9.687c-0.455,-3.095 -1.137,-6.129 -2.578,-7.76c-0.983,-1.112 -2.703,-1.545 -5.006,-1.856c-4.488,-0.607 -10.869,-0.356 -19.028,-0.268c-1.38,0.015 -2.512,-1.094 -2.526,-2.473c-0.015,-1.38 1.093,-2.512 2.473,-2.527c8.469,-0.091 15.092,-0.317 19.751,0.313c3.798,0.513 6.461,1.666 8.082,3.5c1.498,1.695 2.587,4.414 3.263,7.494c0.927,4.22 1.151,9.135 1.188,12.316c2.095,-1.757 4.208,-3.668 4.584,-4.004c1.028,-0.919 2.61,-0.831 3.53,0.197c0.919,1.029 0.831,2.611 -0.197,3.53c-0.73,0.653 -7.42,6.571 -9.543,7.67l-0.856,0.29l-0.754,0.045l-0.724,-0.114c-0.257,-0.066 -0.542,-0.166 -0.85,-0.307c-1.828,-0.839 -5.311,-4.002 -9.222,-7.335c-1.05,-0.894 -1.176,-2.474 -0.281,-3.524c0.894,-1.05 2.474,-1.176 3.524,-0.281c1.905,1.623 4.158,3.459 5.798,4.781Z" style="fill:var(--color-primary);" />
                        </svg>
                    </span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="pl-6 transition-drive transition-drive--to-top" style="--transition-delay: 1.2s;">Gemeinschaft.</span>
                </div>
                <div class="flex gap-4 justify-between mr-12">
                    <span class="pl-14 transition-drive transition-drive--to-left" style="--transition-delay: 1.8s;">Aktiv.</span>
                    <div class="size-10 transition-drive transition-drive--to-left" style="--transition-delay: 1.75s;">
                        <svg width="100%" height="100%" viewBox="0 0 50 50" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
                            <rect id="title-arrow2-box" x="0" y="0" width="50" height="50" style="fill:none;" />
                            <clipPath id="_clip1">
                                <rect x="0" y="0" width="50" height="50" />
                            </clipPath>
                            <g clip-path="url(#_clip1)">
                                <path d="M7.339,34.25c1.992,2.194 4.389,4.6 4.778,4.996c0.969,0.983 0.956,2.567 -0.027,3.536c-0.983,0.968 -2.567,0.955 -3.535,-0.028c-0.689,-0.699 -6.935,-7.111 -8.142,-9.187l-0.33,-0.838l-0.083,-0.747l0.075,-0.727c0.052,-0.26 0.137,-0.55 0.261,-0.864c0.741,-1.881 3.712,-5.546 6.83,-9.651c0.835,-1.098 2.404,-1.313 3.503,-0.478c1.099,0.835 1.313,2.404 0.478,3.503c-1.352,1.779 -2.857,3.845 -4.041,5.49c12.26,-0.37 20.958,-0.043 27.042,-0.653c3.489,-0.35 6.004,-0.933 7.713,-2.433c1.326,-1.164 2.074,-2.879 2.543,-5.293c0.572,-2.94 0.685,-6.782 0.54,-11.804c-0.039,-1.379 1.048,-2.531 2.427,-2.571c1.38,-0.039 2.532,1.048 2.571,2.427c0.158,5.491 -0.005,9.689 -0.63,12.903c-0.727,3.74 -2.098,6.292 -4.153,8.096c-2.35,2.063 -5.711,3.169 -10.512,3.65c-6.148,0.617 -14.932,0.301 -27.308,0.673Z" style="fill:var(--color-primary);" />
                            </g>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <?php $imageIndex = 0; ?>
        <?php foreach ($images as $stageImage): ?>
            <?php $position = $positions[$imageIndex] ?? ['x' => 0, 'y' => 0]; ?>
            <?php $positionStyle = '--position-x: ' . $position[0] . '; --position-y: ' . $position[1] . ';'; ?>
            <?php $delay = ($baseDelay * (1 - pow($decay, $position[1])) / (1 - $decay)) + ($position[0] * $xDelay); ?>
            <div class="stage-welcome__item shadow-lg overflow-clip rounded-sm lg:rounded-md transition-flip" style="<?= $positionStyle ?> <?= '--transition-delay: ' . round($delay + $imageStartDelay, 3) . 's;' ?>">
                <?= snippet(
                    'picture',
                    [
                        'image' => $stageImage,
                        'cropRatio' => 4 / 3,
                        'sizes' => [],
                        'preset' => 'stageWelcome',
                        'responsive' => true,
                        'class' => 'block w-full h-full stage-welcome__image ',
                        'imgClass' => 'w-full h-full object-cover',
                        'alt' => '',
                    ]
                ); ?>
            </div>
            <?php $imageIndex++; ?>
        <?php endforeach; ?>
        <!-- Shadow -->
        <?php foreach ($positionsShadow as $shadow): ?>
            <?php $positionStyle = '--position-x: ' . $shadow[0] . '; --position-y: ' . $shadow[1] . ';'; ?>
            <div class="stage-welcome__shadow" style="<?= $positionStyle ?>"></div>
        <?php endforeach; ?>
    </div>
</div>
