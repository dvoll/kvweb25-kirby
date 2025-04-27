<?php

/** @var \Kirby\Cms\Block $block */
$caption = $block->caption();
$crop    = $block->crop()->isTrue();
$ratio   = $block->ratio()->or('auto');
?>

<div class="dvll-block dvll-block--wide bg-secondary p-12">
    <h2 class="heading-lv2 mb-6">Galerie: Block in Bearbeitung</h2>
    <ul class="flex">
        <?php foreach ($block->images()->toFiles() as $image): ?>
            <li class="">
                <?= snippet(
                    'picture',
                    [
                        'image' => $image,
                        // 'cropRatio' => $calculatedRatio,
                        'preset' => 'default',
                        'imgClass' => 'h-[200px] object-cover',
                    ]
                ); ?>
            </li>
        <?php endforeach ?>
    </ul>
</div>
