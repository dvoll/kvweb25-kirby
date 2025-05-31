<?php

/**
 * @var Kirby\Cms\Page $page
 */
?>

<?php
snippet('layout', slots: true); ?>
<?php snippet('core/stage'); ?>
<ul>
    <?php foreach ($page->children() as $event): ?>
        <?php
        /** @var Kirby\Cms\Page $event */
        ?>
        <li>
            <h2><?= $event->title() ?></h2>
            <div class="">
                <p><?= $event->content()->get('start') ?></p>
                <p><?= $event->content()->get('end') ?></p>
            </div>
            <a href="<?= $event->url() ?>">Event url</a>
        </li>
    <?php endforeach ?>
</ul>
<?php snippet('core/layouts'); ?>
<?php endsnippet() ?>
