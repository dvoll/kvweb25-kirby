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
<div class="flex flex-col"
     x-data="eventCard()"
     x-init="eventSlug = '<?= $eventSlug ?>'">
    <div class="card card--with-hover flex flex-col relative h-full">
        <a <?= Html::attr([
                'href' => $eventUrl,
                'class' => 'absolute inset-0',
                'aria-hidden' => 'true',
                'tabindex' => '-1',
                'title' => 'Zu den Termindetails von: ' . $eventTitle->escape(),
            ]) ?>
            @click.prevent.stop="openModal()"></a>
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
                <h3 class="heading-h3 text-contrast pr-4"><?= Str::excerpt($eventTitle->escape(), 38) ?></h3>
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
                    @click.prevent.stop="openModal()"><?= $buttonLabel ?><?= snippet('elements/icon', ['icon' => 'external']) ?></button>
            </div>
        </div>
    </div>
</div>
