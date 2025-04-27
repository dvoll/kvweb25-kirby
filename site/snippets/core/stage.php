<section class="dvll-section">
    <div class="dvll-section__layout">
        <?= $page->content()->get('stage')->__call('toBlocks') ?>
    </div>
</section>
