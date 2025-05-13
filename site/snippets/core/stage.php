<?php

/**
 * @var Kirby\Cms\Page $page
 */

/** @var Kirby\Cms\Field $stageField */
$stageField = $page->content()->get('stage');

?>
<section class="dvll-section">
    <div class="dvll-section__layout">
        <?= $stageField->toBlocks() ?>
    </div>
</section>
