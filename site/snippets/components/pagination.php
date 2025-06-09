<?php if ($pagination && $pagination->hasPages()): ?>
    <nav class="flex gap-6">

        <?php if ($pagination->hasPrevPage()): ?>
            <a class="btn btn--secondary btn--icon-left" href="<?= $pagination->prevPageURL() ?>">
                <?= snippet('elements/icon', ['icon' => 'arrow-left']) ?>Frühere Termine
            </a>
        <?php else: ?>
            <span class="btn btn--secondary btn--icon-left disabled" aria-hidden>
                <?= snippet('elements/icon', ['icon' => 'arrow-left']) ?>Frühere Termine
            </span>
        <?php endif ?>
        <?php if ($pagination->hasNextPage()): ?>
            <a class="btn btn--secondary" href="<?= $pagination->nextPageURL() ?>">
                Mehr Termine<?= snippet('elements/icon', ['icon' => 'arrow-right']) ?>
            </a>
        <?php else: ?>
            <span class="btn btn--secondary disabled" aria-hidden>
                Mehr Termine<?= snippet('elements/icon', ['icon' => 'arrow-right']) ?>
            </span>
        <?php endif ?>


    </nav>
<?php endif ?>
