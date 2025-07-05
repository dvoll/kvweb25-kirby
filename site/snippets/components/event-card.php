<?php

/**
 * @var dvll\KirbyEvents\Models\EventPage $event
 * @var Kirby\Cms\Site $site
 */

use dvll\KirbyEvents\Models\EventPage;
use dvll\Sitepackage\Helpers\UuidSelectFieldHelper;
use Kirby\Toolkit\Html;
use Kirby\Toolkit\Str;

/** @var Kirby\Content\Field $eventTitle */
$eventTitle = $event->content()->get('title');
$eventSlug = $event->slug();

$teaser = $teaser ?? false;
$buttonLabel = $buttonLabel ?? 'Termin öffnen';
$eventUrl = url('/termine', ['query' => ['event' => $eventSlug]]);
$showGoToOverviewButton = $showGoToOverviewButton ?? true;
$isInitiallyOpen = $isInitiallyOpen ?? false;

$blogpostPages = $event->getConnectedBlogposts();

/** @var Kirby\Content\Field $eventLocation */
$eventLocation = $event->content()->get('location');
/** @var Kirby\Content\Field $eventDescription */
$eventDescription = $event->content()->get('description');

$eventStartDate = $event->getStartDate();
$eventEndDateOnly = $event->getEndDateOnly(useCorrection: true);
$eventStartDay = $eventStartDate->format('d');
$eventEndDateTime = $event->getEndDateTime();

$eventMatchingTag = $event->getTag();
$eventTagPage = $event->getTagPage();


?>
<div class="flex flex-col" x-data="{modalOpen: <?= $isInitiallyOpen ? 'true' : 'false' ?>}">
    <div class="card card--with-hover flex flex-col relative h-full">
        <a <?= Html::attr([
                'href' => $eventUrl,
                'class' => 'absolute inset-0',
                'aria-hidden' => 'true',
                'tabindex' => '-1',
                'title' => 'Zu den Termindetails von: ' . $eventTitle->escape(),
            ]) ?>
            @click.prevent.stop="modalOpen = true"></a>
        <div class="grid grid-cols-[auto_1fr] grid-rows-[1fr_auto_minmax(auto,2fr)_auto] h-full">
            <div class="row-span-4 bg-offwhite px-4 @min-card-small-md:px-4 py-4 flex flex-col items-center justify-center @min-card-small-md:min-w-[90px]">
                <span class="ml-1 font-style font-semibold text-contrast text-3xl"><?= $eventStartDay ?>.</span>
                <span class="font-style font-semibold text-contrast text-sm leading-3.5"><?= EventPage::getMonthString($eventStartDate, cut: true) ?></span>
                <?php if ($event->hasMultipleDays()): ?>
                    <span class="font-style font-semibold text-contrast text-base leading-4">–</span>
                    <span class="ml-1 font-style font-semibold text-contrast text-3xl"><?= date('d', $eventEndDateOnly) ?>.</span>
                    <span class="font-style font-semibold text-contrast text-sm leading-3.5"><?= EventPage::getMonthString($eventEndDateOnly, cut: true); ?></span>
                <?php elseif (!$event->isAllDayEvent()): ?>
                    <span class="mt-2 font-style font-semibold text-contrast text-lg"><?= $eventStartDate->format('H:i') ?></span>
                    <span class="font-style font-semibold text-contrast text-sm leading-3.5">Uhr</span>
                <?php endif; ?>
            </div>
            <div class="col-start-2 row-start-2 px-3 @min-card-md:px-4 flex flex-col">
                <h3 class="heading-lv3 text-contrast pr-4"><?= Str::excerpt($eventTitle->escape(), 38) ?></h3>
            </div>
            <div class="col-start-2 row-start-3 px-3 @min-card-md:px-4 pb-1 flex flex-row flex-wrap-reverse items-center gap-x-2 gap-y-1">
                <?php if ($eventMatchingTag && $eventMatchingTag->isNotEmpty()): ?>
                    <span class="block font-body text-sm text-contrast bg-secondary px-2 rounded-md">
                        <?= $eventMatchingTag->name()->escape() ?>
                    </span>
                <?php endif; ?>
                <?php if ($eventLocation->isNotEmpty()): ?>
                    <p class="font-body text-sm text-gray-600 flex items-center gap-0.5">
                        <?= snippet('elements/icon', ['icon' => 'location', 'class' => 'size-4']) ?>
                        <?= $eventLocation->excerpt(34) ?>
                    </p>
                <?php endif; ?>
                <?php if ((!$eventMatchingTag || $eventMatchingTag->isEmpty()) && $eventLocation->isEmpty() && $eventDescription->isNotEmpty()): ?>
                    <p class="font-body text-sm text-gray-600">
                        <?= $eventDescription->excerpt(34) ?>
                    </p>
                <?php endif; ?>
            </div>
            <div class="col-start-2 row-start-4 justify-self-end px-3 pb-1.5 @min-card-md:px-4">
                <button <?= Html::attr([
                            'class' => 'btn btn--ghost ml-auto ',
                            'aria-label' => 'Dialog mit Termindetails von: ' . $eventTitle->escape() . ' öffnen.',
                        ]) ?>
                    @click.prevent.stop="modalOpen = true"><?= $buttonLabel ?><?= snippet('elements/icon', ['icon' => 'external']) ?></button>
            </div>
        </div>
    </div>
    <dialog x-ref="eventModal" class="any-modal m-auto w-full max-w-[calc(100%-1rem)] md:max-w-[40rem] rounded-2xl shadow-gallery-image bg-baseline" x-on:close="modalOpen = false" x-effect="
        if (modalOpen) {
            console.log('open modal');
            $el.showModal();
            // history.replaceState(null, '', '/termine/event:<?= $eventSlug ?>');
            if ('URLSearchParams' in window) {
                const url = new URL(window.location);
                url.searchParams.set('event', '<?= $eventSlug ?>');
                url.searchParams.delete('event-page-set');
                history.replaceState(null, '' , url);
            }
        } else {
            console.log('close modal');
            $el.close();
            const url = new URL(window.location);
            if (url.searchParams.get('event') === '<?= $eventSlug ?>') {
                url.searchParams.delete('event');
                url.searchParams.delete('event-page-set');
                history.replaceState(null, '' , url);
            }
        } ">
        <div class=" pb-4" @click.outside="if (modalOpen) modalOpen = false">
            <div class="bg-offwhite flex flex-col items-start pl-4 md:pl-12 pr-4 md:pr-5 pt-4 pb-4 gap-2 sticky top-0">
                <button autofocus class="self-end btn btn--ghost hover:bg-tertiary hover:text-baseline" @click="modalOpen = false">Schließen<?= snippet('elements/icon', ['icon' => 'external']) ?></button>
                <h3 class="heading-lv3 text-contrast"><?= $eventTitle->escape() ?></h3>
                <?php if ($eventLocation->isNotEmpty()): ?>
                    <p class="font-body text-sm text-gray-600 flex items-center gap-0.5">
                        <?= snippet('elements/icon', ['icon' => 'location', 'class' => 'size-4 shrink-0']) ?>
                        <?= $eventLocation ?>
                    </p>
                <?php endif; ?>
            </div>
            <div class="flex flex-wrap md:flex-nowrap pl-4 md:pl-12 pr-4 md:pr-8 mt-6 gap-8">
                <div class="flex flex-col gap-4 items-start shrink-0">
                    <p class="font-style text-contrast text-base">
                        <?php if ($event->isAllDayEvent()): ?>
                            <span class="font-semibold"><?= EventPage::getDateWeekdayString($eventStartDate); ?>, <?= $eventStartDay ?>. <?= EventPage::getMonthString($eventStartDate); ?></span>
                            <?php if ($event->hasMultipleDays()): ?>
                                <br>
                                <span class="font-normal text-sm">bis <?= EventPage::getDateWeekdayString($eventEndDateOnly); ?>, <?= date('d', $eventEndDateOnly); ?>. <?= EventPage::getMonthString($eventEndDateOnly); ?></span>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php if ($event->hasMultipleDays()): ?>
                                <span class="font-semibold"><?= $eventStartDay ?>. <?= EventPage::getMonthString($eventStartDate); ?>, <?= $eventStartDate->format('H:i') ?> Uhr</span>
                                <br>
                                <span class="font-normal text-sm">bis <?= $eventEndDateTime->format('d') ?>. <?= EventPage::getMonthString($eventEndDateTime); ?>, <?= $eventEndDateTime->format('H:i') ?> Uhr</span>
                            <?php else: ?>
                                <span class="font-semibold"><?= EventPage::getDateWeekdayString($eventStartDate); ?>, <?= $eventStartDay ?>. <?= EventPage::getMonthString($eventStartDate); ?></span>
                                <br>
                                <span class="font-normal text-sm"><?= $eventStartDate->format('H:i') ?> Uhr bis <?= $eventEndDateTime->format('H:i') ?> Uhr</span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </p>
                    <?php if ($eventMatchingTag && $eventMatchingTag->isNotEmpty()): ?>
                        <?php if ($eventTagPage && $eventTagPage->isNotEmpty()): ?>
                            <a href="<?= $eventTagPage->url() ?>" class="btn btn--secondary"><span><?= $eventTagPage->title() ?></span></a>
                        <? else: ?>
                            <span class="block font-body text-sm text-contrast bg-secondary px-2 rounded-md">
                                <?= $eventMatchingTag->name()->escape() ?>
                            </span>
                        <? endif; ?>
                    <?php endif; ?>
                </div>
                <div class="w-full flex flex-col gap-4 items-start">
                    <?php if ($eventDescription->isNotEmpty()): ?>
                        <p class="font-body text-base text-contrast">
                            <?= $eventDescription->value(); // Description is trusted here
                            ?>
                        </p>
                    <?php endif; ?>
                    <div class="flex flex-col gap-2 items-start">
                        <p class="font-style text-contrast text-sm font-semibold">Für den eigenen Kalender:</p>
                        <a href="/termine/<?= $eventSlug ?>.ics" download="<?= $eventSlug ?>.ics" class="btn btn--secondary">Termin als Datei herunterladen<?= snippet('elements/icon', ['icon' => 'download']) ?></a>
                        <?php if (!empty($googleCalendarLink)): ?>
                            <a
                                href="<?= $googleCalendarLink ?>"
                                target="_blank"
                                class="btn btn--secondary">Zum Google Kalender hinzufügen<?= snippet('elements/icon', ['icon' => 'external']) ?></a>
                        <?php endif; ?>
                        <?php if (!empty($outlookCalendarLink)): ?>
                            <a
                                href="<?= $outlookCalendarLink ?>"
                                target="_blank"
                                class="btn btn--secondary">Zum Outlook Kalender hinzufügen<?= snippet('elements/icon', ['icon' => 'external']) ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php if (($blogpostPages && $blogpostPages->count() > 0) || $eventTagPage): ?>

                <div class="mt-12 pl-4 md:pl-12 pr-4 md:pr-8 ">
                    <h3 class="heading-lv3 text-contrast">Weitere Informationen</h3>
                </div>
                <div class="px-4 grid grid-cols-1 md:grid-cols-(--dvll-card-grid-cols) gap-4 md:justify-center mt-4">
                    <?php foreach ($blogpostPages as $blogpost): ?>
                        <?= snippet('components/blogpost-card', [
                            'title' => $blogpost->title(),
                            'text' => $blogpost->text()->excerpt(140),
                            'url' => $blogpost->url(),
                        ]) ?>
                    <? endforeach; ?>

                    <?php if ($eventTagPage): ?>
                        <?= snippet('components/teaser-card', [
                            'title' => $eventTagPage->myTitle(),
                            'buttonTitle' => $eventTagPage->title(),
                            'text' => $eventTagPage->myTeaserText(),
                            'image' => $eventTagPage->myTeaserImage(),
                            'url' => $eventTagPage->url(),
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
