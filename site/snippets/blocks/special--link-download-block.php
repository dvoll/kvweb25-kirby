<?php

/** @var \Kirby\Cms\Block $block */

$type = $block->itemType()->value();
/** @var dvll\Sitepackage\Models\CustomBasePage|null $internalPage */
$internalPage = $block->urlPage()->toPage();
$internalPageTitle = $internalPage ? $internalPage->title() : null;
$internalPageLongTitle = $internalPage ? $internalPage->myTitle() : null;
?>

<?= snippet(
    'components/asset-card',
    [
        'title' => $block->title()->isNotEmpty() ? $block->title()->escape()->value() : $internalPageLongTitle,
        'text' => $block->summary()->isNotEmpty() ? $block->summary()->escape()->value() : null,
        'url' => $type === 'external' ? $block->url()->toUrl() : $block->urlPage()->toUrl(),
        'class' => '',
        'linkType' => $type,
        'assetFile' => $block->download()->toFile(),
        'buttonTitle' => $type === 'page' ? $internalPageTitle : null,
        'buttonText' => $type === 'page' ? 'Zur Seite &#8222;' . $internalPageTitle . '&#8220;' : null,
    ]
); ?>
