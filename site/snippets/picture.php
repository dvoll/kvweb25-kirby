<?php

use Kirby\Toolkit\A;

/**
 * @var Kirby\Cms\File|Kirby\Filesystem\Asset $image The image to display
 * @var string|null $alt An optional alt text, defaults to the file's alt text
 * @var float|null $cropRatio Specify a ratio for the image crop
 *
 * @var string|null $sizes The sizes attribute for the <img> element
 *
 * @var string|null $class Additional classes for the <picture> element
 * @var string|null $imgClass Additional classes for the <img> element
 * @var string|array<string>|null $imgClass Additional classes for the <img> element
 * @var array<string, string>|null $attr Additional attributes for the <picture> element
 *
 * @var bool|null $responsive Whether to use responsive images, defaults to false
 * @var bool|null $lazy Whether to use lazy loading, defaults to true
 * */

$cropRatio ??= null;
$preset ??= 'default';
$clientBlur ??= true;
$attr ??= [];
$imgClass ??= null;
$lazy ??= true;
$responsive ??= false;

$srcSets = option("thumbs.srcsets.{$preset}");

$srcsetsDefault = [];
$srcsetsWebp = [];

foreach ($srcSets as $setKey => $set) {
    $srcsetsDefault[$setKey] = [
        'width' => $set['width'],
        'height' => $set['height'] ?? ($cropRatio ? floor($set['width'] / $cropRatio) : null),
        'crop' => $set['crop'] ?? ($cropRatio ? true : false),
    ];
    $srcsetsWebp[$setKey] = [
        ...$srcsetsDefault[$setKey],
        'format' => 'webp'
    ];
}
$defaultSrcset = A::first($srcsetsDefault);

if (is_a($image, 'Kirby\Cms\File') || is_a($image, 'Kirby\Filesystem\Asset')) : ?>

    <picture <?= attr([
                    'class' => [$class ?? ''],
                    'style' => '--ratio: ' . ($cropRatio ?? $image->ratio()) . ';',
                    ...$attr
                ]) ?>>

        <?php if ($image->extension() == 'svg') : ?>
            <?= svg($image) ?>
        <?php else : ?>

            <source <?= attr([
                        'type' => "image/webp",
                        'srcset' => $image->srcset($srcsetsWebp),
                        'sizes' => $sizes ?? '100vw',
                    ]) ?>>

            <img <?= attr([
                        'src' => $image->thumb($defaultSrcset)->url(),
                        'srcset' => $image->srcset($srcsetsDefault),
                        'sizes' => $sizes,
                        'width' => $image->width(),
                        'height' => $cropRatio ? floor($image->width() / $cropRatio) : $image->height(),
                        'alt' => $alt ?? (is_a($image, 'Kirby\Cms\File') ? $image->alt() : null),
                        'loading' => $lazy ? "lazy" : null,
                        'class' => ['w-full', $responsive ? 'h-full object-cover' : '', $imgClass ?? ''],
                        'style' => $responsive ? 'object-position: '  . ($image->focus()->isNotEmpty() ? $image->focus() : '50% 50%') : '',
                    ]) ?>>

        <?php endif ?>
    </picture>

<?php
// Dummy element that will be rendered when specified image is not an image
else : ?>
    <picture <?= attr(['class' => $class ?? '']) ?>></picture>
<?php endif ?>
