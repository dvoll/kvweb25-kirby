<?php

/**
 * @var Kirby\Cms\Page $page
 */
?>

<?php
snippet('layout', slots: true); ?>
<?php snippet('core/stage'); ?>
<ul>
    <li>
        <h1><?= $page->title() ?></h1>
        <p>Category: <?= $page->category() ?></p>
        <div class="">
            <p><?= $page->content()->get('start') ?></p>
            <p><?= $page->content()->get('end') ?></p>
        </div>
        <a href="<?= $page->url() ?>">Event url</a>
    </li>
</ul>
<?php snippet('core/layouts'); ?>
<?php endsnippet() ?>
