<?php

/**
 * @var dvll\KirbyEvents\Models\EventPage $event
 */

use Kirby\Toolkit\Html;

$teaser = $teaser ?? false;
$buttonLabel = $buttonLabel ?? 'Termin öffnen';


?>
<div class="card card--with-hover flex flex-col relative">
    <a <?= Html::attr([
            'href' => $event->url(),
            'class' => 'absolute inset-0',
            'aria-hidden' => 'true',
            'tabindex' => '-1',
            'title' => 'Zu den Termindetails von: ' . $event->title(),
        ]) ?>></a>
    <div class="grid grid-cols-[auto_1fr] grid-rows-[1fr_auto_minmax(auto,2fr)_auto] h-full">
        <div class="row-span-4 bg-offwhite px-4 @min-card-small-md:px-4 py-4 flex flex-col items-center justify-center @min-card-small-md:min-w-[90px]">
            <span class="ml-1 font-style font-semibold text-contrast text-3xl"><?= date('d', $event->getStartDate()) ?>.</span>
            <span class="font-style font-semibold text-contrast text-sm leading-3.5"><?= $event->getStartDateMonthString() ?></span>
            <?php if ($event->showAsFullDayEventWithoutTime()): ?>
                <?php if ($event->hasMultipleDays()): ?>
                    <span class="font-style font-semibold text-contrast text-base leading-4">–</span>
                    <span class="ml-1 font-style font-semibold text-contrast text-3xl"><?= date('d', $event->getEndDate()) ?>.</span>
                    <span class="font-style font-semibold text-contrast text-sm leading-3.5"><?= $event->getEndDateMonthString(); ?></span>
                <?php endif; ?>
            <?php else: ?>
                <span class="mt-2 font-style font-semibold text-contrast text-lg"><?= date('h:i', $event->getStartDate()); ?></span>
                <span class="font-style font-semibold text-contrast text-sm leading-3.5">Uhr</span>
            <?php endif; ?>
        </div>
        <div class="col-start-2 row-start-2 px-3 @min-card-md:px-4 flex flex-col">
            <h3 class="heading-lv3 pr-4"><?= $event->title()->excerpt(42) ?></h3>
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
                <p class="font-body text-sm text-gray-600 -mt-2">
                    <?= $event->description()->excerpt(34) ?>
                </p>
            <?php endif; ?>
        </div>
        <div class="col-start-2 row-start-4 justify-self-end px-3 pb-1.5 @min-card-md:px-4">
            <a <?= Html::attr([
                    'href' => $event->url(),
                    'class' => 'btn btn--ghost ml-auto ' . ($teaser ? 'text-gray-600 text-center' : ''),
                    'aria-label' => 'Zu den Termindetails von: ' . $event->title(),
                ]) ?>><?= $buttonLabel ?><?= snippet('elements/icon') ?></a>
        </div>
    </div>
</div>
