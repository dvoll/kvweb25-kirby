<?php

/** @var \Kirby\Cms\Block $block */ ?>

<div class="dvll-block dvll-block--narrow mb-4">
    <<?= $level = $block->level()->or('h2') ?> class="heading-lv2"><?= $block->text() ?></<?= $level ?>>
</div>
