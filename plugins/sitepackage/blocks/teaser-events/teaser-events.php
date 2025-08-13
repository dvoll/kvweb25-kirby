<?php

/**
 * @var dvll\Sitepackage\Models\TeaserEventsBlock $block
 * @var \Kirby\Cms\Site $site
 * @var DefaultPage $page
 * @var \Kirby\Cms\Page $teaserPage
 */

use Kirby\Toolkit\Html;

$teaserEvents = $block->myEvents()->limit(3);

?>

<div class="dvll-block dvll-block--wide">
    <div class="grid grid-cols-1 md:grid-cols-(--dvll-card-grid-cols--small) gap-4 md:gap-6 md:justify-center">
        <?php foreach ($teaserEvents as $teaserEvent): ?>
            <?= snippet('components/event-card', [
                'event' => $teaserEvent,
                'buttonLabel' => 'Termindetails ansehen',
            ]) ?>
        <?php endforeach; ?>
        <?php if ($teaserEvents->count() === 0): ?>
            <div class="card card--with-hover flex flex-col px-5 pt-5 pb-4 @min-card-md:px-6 @min-card-md:pb-4 @min-card-md:pt-6 relative bg-secondary justify-center items-center">
                <a href="/termine"
                    class="absolute inset-0"
                    aria-hidden="true"
                    tabindex="-1"
                    title="Zu allen Terminen"></a>

                <h3 class="heading-h3 text-gray-600 text-center pr-4">Hier gibt es gerade keine passenden Termine</h3>
                <p class="max-w-96 text-sm text-gray-600 text-center mt-2">
                    Schau doch mal auf der Seite mit allen Terminen vorbei
                </p>

                <div class="flex justify-between gap-4 mt-4 items-center">
                    <a href="/termine"
                        class="btn btn--ghost ml-auto text-gray-600 text-center"
                        aria-label="Zu allen Terminen">
                        Zur Termine Seite<?= snippet('elements/icon') ?>
                    </a>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($teaserEvents->count() === 1 || $teaserEvents->count() === 2): ?>
            <div class="card card--with-hover flex flex-col px-5 pt-5 pb-4 @min-card-md:px-6 @min-card-md:pb-4 @min-card-md:pt-6 relative bg-secondary justify-center items-center">
                <a href="/termine"
                    class="absolute inset-0"
                    aria-hidden="true"
                    tabindex="-1"
                    title="Zu allen Terminen"></a>

                <h3 class="heading-h3 text-gray-600 text-center pr-4">Sieh dir alle Termine an</h3>
                <p class="max-w-96 text-sm text-gray-600 text-center mt-2">
                    Gehe zur Seite mit allen kommenden Terminen
                </p>

                <div class="flex justify-between gap-4 mt-4 items-center">
                    <a href="/termine"
                        class="btn btn--ghost ml-auto text-gray-600 text-center"
                        aria-label="Zu allen Terminen">
                        Zur Termine Seite<?= snippet('elements/icon') ?>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?= snippet('components/event-dialog', ['showGoToOverviewButton' => true]) ?>
</div>

<?php if ($teaserEvents->count() >= 3): ?>
<div class="dvll-block dvll-block--narrow">
    <div class="flex flex-row flex-wrap justify-end">
        <a <?= Html::attr([
                'href' => $site->find('termine')->url(),
                'class' => 'btn btn--secondary self-start',
            ]) ?>>Alle Termine anzeigen<?= snippet('elements/icon') ?></a>
    </div>
</div>
<?php endif; ?>
