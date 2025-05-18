<?php

/** @var \Kirby\Cms\Block $block */
?>

<?= snippet(
    'components/asset-card',
    [
        'title' => $block->title(),
        'text' => $block->summary(),
        'url' => $block->url()->toUrl(),
        'class' => '',
        'linkType' => Str::startsWith($block->url(), 'page://') ? 'page' : 'external',
    ]
); ?>
