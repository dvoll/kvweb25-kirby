<?php

/**
 * @var dvll\KirbyEvents\Models\EventsPage $page
 */

snippet('layout', slots: true); ?>

<?php snippet('core/stage'); ?>

<section class="dvll-section">
    <div class="dvll-section__layout">
        <div class="dvll-block dvll-block--wide grid grid-cols-(--dvll-card-grid-cols--small) gap-4 md:gap-6 md:justify-center auto-rows-fr">
            <?php foreach ($page->children()->published()->sortBy('getStartDate', 'asc') as $event): ?>
                <?php
                    /** @var dvll\KirbyEvents\Models\EventPage $event */
                ?>
                <?= snippet('components/event-card', [
                    'event' => $event,
                    'buttonLabel' => 'Termindetails ansehen',
                ]) ?>
            <?php endforeach ?>
        </div>
    </div>
</section>
<?php endsnippet() ?>
