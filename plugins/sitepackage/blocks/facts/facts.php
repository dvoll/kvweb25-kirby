<?php

/** @var \Kirby\Cms\Block $block */

/** @var \Kirby\Cms\Structure|null $factStructure */
$factStructure = $block->facts()->toStructure() ?? null;
?>

<?php if ($factStructure): ?>
    <div class="dvll-block dvll-block--narrow">
        <ul class="facts-list">
            <?php foreach ($factStructure as $fact): ?>
                <?= snippet('components/fact', [
                    'fact' => $fact,
                    'elName' => 'li'
                ]) ?>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
