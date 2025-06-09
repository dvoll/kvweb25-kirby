<?php

/**
 * @var dvll\KirbyEvents\Models\EventPage $event
 */

use Kirby\Toolkit\Html;

$teaser = $teaser ?? false;
$buttonLabel = $buttonLabel ?? 'Termin öffnen';
$eventUrl = url('/termine', ['params' => ['event' => $event->slug()]]);
$showGoToOverviewButton = $showGoToOverviewButton ?? true;
$isOpen = $isOpen ?? false;

$blogpost = $event->getConnectedBlogpost();

$page = $event->category()->isNotEmpty() ? $site->find('freizeiten/' . $event->category()) : null;

?>
<div class="flex flex-col" x-data="{modalOpen: <?= $isOpen ? 'true' : 'false' ?>}">
    <div class="card card--with-hover flex flex-col relative h-full">
        <a <?= Html::attr([
                'href' => $eventUrl,
                'class' => 'absolute inset-0',
                'aria-hidden' => 'true',
                'tabindex' => '-1',
                'title' => 'Zu den Termindetails von: ' . $event->title(),
            ]) ?>
            @click.prevent.stop="modalOpen = true"></a>
        <div class="grid grid-cols-[auto_1fr] grid-rows-[1fr_auto_minmax(auto,2fr)_auto] h-full">
            <div class="row-span-4 bg-offwhite px-4 @min-card-small-md:px-4 py-4 flex flex-col items-center justify-center @min-card-small-md:min-w-[90px]">
                <span class="ml-1 font-style font-semibold text-contrast text-3xl"><?= date('d', $event->getStartDate()) ?>.</span>
                <span class="font-style font-semibold text-contrast text-sm leading-3.5"><?= $event->getStartDateMonthString(cut: true) ?></span>
                <?php if ($event->showAsFullDayEventWithoutTime()): ?>
                    <?php if ($event->hasMultipleDays()): ?>
                        <span class="font-style font-semibold text-contrast text-base leading-4">–</span>
                        <span class="ml-1 font-style font-semibold text-contrast text-3xl"><?= date('d', $event->getEndDate()) ?>.</span>
                        <span class="font-style font-semibold text-contrast text-sm leading-3.5"><?= $event->getEndDateMonthString(cut: true); ?></span>
                    <?php endif; ?>
                <?php else: ?>
                    <span class="mt-2 font-style font-semibold text-contrast text-lg"><?= date('h:i', $event->getStartDate()); ?></span>
                    <span class="font-style font-semibold text-contrast text-sm leading-3.5">Uhr</span>
                <?php endif; ?>
            </div>
            <div class="col-start-2 row-start-2 px-3 @min-card-md:px-4 flex flex-col">
                <h3 class="heading-lv3 text-contrast pr-4"><?= $event->title()->excerpt(42) ?></h3>
            </div>
            <div class="col-start-2 row-start-3 px-3 @min-card-md:px-4 pb-1 flex flex-row flex-wrap-reverse items-center gap-x-2 gap-y-1">
                <?php if ($event->category()->isNotEmpty()): ?>
                    <span class="block font-body text-sm text-contrast bg-secondary px-2 rounded-md">
                        <?= $event->category() ?>
                    </span>
                <?php endif; ?>
                <?php if ($event->location()->isNotEmpty()): ?>
                    <p class="font-body text-sm text-gray-600 flex items-center gap-0.5">
                        <?= snippet('elements/icon', ['icon' => 'location', 'class' => 'size-4']) ?>
                        <?= $event->location()->excerpt(34) ?>
                    </p>
                <?php endif; ?>
                <?php if ($event->category()->isEmpty() && $event->location()->isEmpty() && $event->description()->isNotEmpty()): ?>
                    <p class="font-body text-sm text-gray-600">
                        <?= $event->description()->excerpt(34) ?>
                    </p>
                <?php endif; ?>
            </div>
            <div class="col-start-2 row-start-4 justify-self-end px-3 pb-1.5 @min-card-md:px-4">
                <a <?= Html::attr([
                        'href' => $eventUrl,
                        'class' => 'btn btn--ghost ml-auto ' . ($teaser ? 'text-gray-600 text-center' : ''),
                        'aria-label' => 'Zu den Termindetails von: ' . $event->title(),
                    ]) ?>><?= $buttonLabel ?><?= snippet('elements/icon') ?></a>
            </div>
        </div>
    </div>
    <dialog x-ref="eventModal" class="any-modal m-auto w-full max-w-[calc(100%-1rem)] md:max-w-[40rem] rounded-2xl shadow-gallery-image bg-baseline" x-on:close="modalOpen = false" x-effect="
        if (modalOpen) {
            console.log('open modal');
            $el.showModal();
            // history.replaceState(null, '', '/termine/event:<?= $event->slug() ?>');
            if ('URLSearchParams' in window) {
                const url = new URL(window.location);
                url.searchParams.set('event', '<?= $event->slug() ?>');
                url.searchParams.delete('event-page-set');
                history.replaceState(null, '' , url);
            }
        } else {
            console.log('close modal');
            $el.close();
            const url = new URL(window.location);
            if (url.searchParams.get('event') === '<?= $event->slug() ?>') {
                url.searchParams.delete('event');
                url.searchParams.delete('event-page-set');
                history.replaceState(null, '' , url);
            }
        } ">
        <div class=" pb-4" @click.outside="if (modalOpen) modalOpen = false">
            <div class="bg-offwhite flex flex-col items-start pl-4 md:pl-12 pr-4 md:pr-5 pt-4 pb-4 gap-2 sticky top-0">
                <button autofocus class="self-end btn btn--ghost hover:bg-tertiary hover:text-baseline" @click="modalOpen = false">Schließen<?= snippet('elements/icon', ['icon' => 'external']) ?></button>
                <h3 class="heading-lv3 text-contrast"><?= $event->title() ?></h3>
                <?php if ($event->location()->isNotEmpty()): ?>
                    <p class="font-body text-sm text-gray-600 flex items-center gap-0.5">
                        <?= snippet('elements/icon', ['icon' => 'location', 'class' => 'size-4 shrink-0']) ?>
                        <?= $event->location() ?>
                    </p>
                <?php endif; ?>
            </div>
            <div class="flex flex-wrap md:flex-nowrap pl-4 md:pl-12 pr-4 md:pr-8 mt-6 gap-8">
                <div class="flex flex-col gap-4 items-start shrink-0">
                    <p class="font-style text-contrast text-base">
                        <?php if ($event->isAllDayEvent()): ?>
                            <span class="font-semibold"><?= $event->getStartDateWeekdayString(); ?>, <?= date('d', $event->getStartDate()) ?>. <?= $event->getStartDateMonthString(); ?></span>
                            <?php if ($event->hasMultipleDays()): ?>
                                <br>
                                <span class="font-normal text-sm">bis <?= $event->getEndDateWeekdayString(); ?>, <?= date('d', $event->getEndDate()) ?>. <?= $event->getEndDateMonthString(); ?></span>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php if ($event->hasMultipleDays()): ?>
                                <span class="font-semibold"><?= date('d', $event->getStartDate()) ?>. <?= $event->getStartDateMonthString(); ?>, <?= date('h:i', $event->getStartDate()); ?> Uhr</span>
                                <br>
                                <span class="font-normal text-sm">bis <?= date('d', $event->getEndDate()) ?>. <?= $event->getEndDateMonthString(); ?>, <?= date('h:i', $event->getEndDate()); ?> Uhr</span>
                            <?php else: ?>
                                <span class="font-semibold"><?= $event->getStartDateWeekdayString(); ?>, <?= date('d', $event->getStartDate()) ?>. <?= $event->getStartDateMonthString(); ?></span>
                                <br>
                                <span class="font-normal text-sm"><?= date('h:i', $event->getStartDate()); ?> Uhr bis <?= date('h:i', $event->getEndDate()); ?> Uhr</span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </p>
                    <?php if ($event->category()->isNotEmpty()): ?>
                        <span class="block font-body text-sm text-contrast bg-secondary px-2 rounded-md">
                            <?= $event->category() ?>
                        </span>
                    <?php endif; ?>
                </div>
                <div class="w-full flex flex-col gap-4 items-start">
                    <?php if ($event->description()->isNotEmpty()): ?>
                        <p class="font-body text-base text-contrast">
                            <?= $event->description() ?>
                        </p>
                    <?php endif; ?>
                    <div class="flex flex-col gap-2 items-start">
                        <p class="font-style text-contrast text-sm font-semibold">Für den eigenen Kalender:</p>
                        <a href="/termine/<?= $event->slug() ?>.ics" download="<?= $event->slug() ?>.ics" class="btn btn--secondary">Termin als Datei herunterladen<?= snippet('elements/icon', ['icon' => 'download']) ?></a>
                        <?php if (!empty($googleCalendarLink)): ?>
                            <a
                                href="<?= $googleCalendarLink ?>"
                                target="_blank"
                                class="btn btn--secondary"
                            >Zum Google Kalender hinzufügen<?= snippet('elements/icon', ['icon' => 'external']) ?></a>
                        <?php endif; ?>
                        <?php if (!empty($outlookCalendarLink)): ?>
                            <a
                                href="<?= $outlookCalendarLink ?>"
                                target="_blank"
                                class="btn btn--secondary"
                            >Zum Outlook Kalender hinzufügen<?= snippet('elements/icon', ['icon' => 'external']) ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php if ($blogpost || $page): ?>

                <div class="mt-12 pl-4 md:pl-12 pr-4 md:pr-8 ">
                    <h3 class="heading-lv3 text-contrast">Weitere Informationen</h3>
                </div>
                <div class="px-4 grid grid-cols-1 md:grid-cols-(--dvll-card-grid-cols) gap-4 md:justify-center mt-4">

                    <?php if ($blogpost): ?>
                        <?= snippet('components/blogpost-card', [
                            'title' => $blogpost->title(),
                            'text' => $blogpost->text()->excerpt(140),
                            'url' => $blogpost->url(),
                        ]) ?>
                    <?php endif; ?>
                    <?php if ($page): ?>
                        <?= snippet('components/teaser-card', [
                            'title' => $page->myTitle(),
                            'buttonTitle' => $page->title(),
                            'text' => $page->myTeaserText(),
                            'image' => $page->myTeaserImage(),
                            'url' => $page->url(),
                        ]) ?>
                    <?php endif; ?>

                </div>
            <?php endif; ?>
            <?php if ($showGoToOverviewButton): ?>
                <div class="flex flex-col px-4 mt-4">
                    <a <?= Html::attr([
                            'href' => 'termine',
                            'class' => 'btn btn--ghost self-center',
                        ]) ?>>Alle Termine ansehen<?= snippet('elements/icon') ?></a>
                </div>
            <?php endif; ?>
        </div>
    </dialog>
</div>
