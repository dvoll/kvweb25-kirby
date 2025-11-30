<?php

$labelNext = $labelNext ?? 'Mehr Termine';
$labelPrev = $labelPrev ?? 'Frühere Termine';

?>

<?php if ($pagination && $pagination->hasPages()): ?>
    <nav class="flex gap-6">

        <?php if ($pagination->hasPrevPage()): ?>
            <a class="btn btn--secondary btn--icon-left" href="<?= $pagination->prevPageURL() ?>">
                <?= snippet('elements/icon', ['icon' => 'arrow-left']) ?><?= $labelPrev ?>
            </a>
        <?php else: ?>
            <span class="btn btn--secondary btn--icon-left disabled" aria-hidden>
                <?= snippet('elements/icon', ['icon' => 'arrow-left']) ?><?= $labelPrev ?>
            </span>
        <?php endif ?>
        <?php if ($pagination->hasNextPage()): ?>
            <a class="btn btn--secondary" href="<?= $pagination->nextPageURL() ?>">
                <?= $labelNext ?><?= snippet('elements/icon', ['icon' => 'arrow-right']) ?>
            </a>
        <?php else: ?>
            <span class="btn btn--secondary disabled" aria-hidden>
                <?= $labelNext ?><?= snippet('elements/icon', ['icon' => 'arrow-right']) ?>
            </span>
        <?php endif ?>


    </nav>
<?php endif ?>
