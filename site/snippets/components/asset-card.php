<?php

use Kirby\Toolkit\A;
use Kirby\Toolkit\Html;

/**
 * @var \Kirby\Cms\Block $block
 *
 * @var \Kirby\Cms\File|null $assetFile
 * @var Field|null $link
 */

$assetFile = $assetFile ?? null;
$isFile = $assetFile ? true : false;
$linkType = $linkType ?? ($isFile ? 'file' : 'external');
$iconName = $isFile ? 'download' : ($linkType === 'external' ? 'external' : 'arrow-right');

$title = $isFile ? ($assetFile->title()->isNotEmpty() ? $assetFile->title() : Str::before($assetFile->filename(), '.')) : ($title ?? 'Link');
$text = $isFile ? $assetFile->filename() : ($text ?? '');
$buttonTitle = $isFile ? 'Anzeigen/Herunterladen' : ($linkType === 'page' ? 'Gehe zur Seite' : 'Gehe zur externen Seite');
$url = $isFile ? $assetFile->url() : ($url ?? '/');

$class = $class ?? '';

?>
<div class="card card--with-hover relative <?= $class ?>">
    <a <?= Html::attr([
            'href' => $url,
            'target' =>  in_array($linkType, ['external', 'file']) ? '_blank' : null,
            'rel' =>  in_array($linkType, ['external', 'file']) ? 'noopener noreferrer' : null,
            'class' => 'absolute inset-0 z-1',
            'aria-hidden' => 'true',
            'tabindex' => '-1',
            'title' => $buttonTitle,
        ]) ?>></a>
    <div class="h-full flex items-stretch justify-between">
        <div class="self-center pb-4 pt-3 pl-6 w-3/4 basis-3/4 md:basis-2/3">
            <h3 class="heading-lv4 mb-1 -mr-3"><?= $title ?></h3>
            <?php if (!empty($text)): ?>
                <p class="typo text-sm line-clamp-3 md:line-clamp-none italic">
                    <?= $text ?>
                </p>
            <?php endif; ?>
            <a <?= Html::attr([
                    'href' => $url,
                    'target' =>  in_array($linkType, ['external', 'file']) ? '_blank' : null,
                    'rel' => in_array($linkType, ['external', 'file']) ? 'noopener noreferrer' : null,
                    'class' => 'btn btn--ghost -ml-2 mt-4 mr-2',
                ]) ?>><?= $buttonTitle ?><?= snippet('elements/icon', ['icon' => $iconName]) ?></a>
        </div>
        <div class="w-1/4 basis-1/4 md:basis-1/3 bg-offwhite angled-cut angled-cut--small flex items-center justify-center pl-4 md:pl-6">
            <?php
            $fileType = $assetFile ? strtolower($assetFile->extension()) : null;
            ?>

            <?php if (in_array($fileType, ['png', 'jpg', 'jpeg', 'gif', 'webp', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'svg'])): ?>
                <?= snippet('elements/file-icon', [
                    'fileType' => $fileType,
                    'class' => 'size-8 md:size-14',
                ]) ?>
            <?php else: ?>
                <?= snippet('elements/icon', [
                    'icon' => $iconName,
                    'class' => 'w-6 md:w-8',
                ]) ?>
            <?php endif; ?>
        </div>
    </div>
</div>
