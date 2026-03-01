<?php

/**
 * @var dvll\KirbyEvents\Models\EventsPage $page
 * @var Kirby\Cms\App $kirby
 */

use dvll\KirbyEvents\Models\EventPage;
use dvll\KirbyEvents\Models\EventsPage;
use Kirby\Http\Uri;

$paginationLimit = 9;

$selectedEventSlug = get('event', null);

$events = $events = $page->children()->published()->sortBy('start', 'asc');

$events = $events->paginate([
    'limit' => $paginationLimit,
    'url' => new Uri($kirby->url('current'), [
        'params' => params(),
    ])
]);

snippet('base', slots: true); ?>

<?php snippet('core/stage'); ?>

<section class="dvll-section">
    <div class="dvll-section__layout">
        <?php
        $currentQuarter = null;
        $quarterStartMonth = null;
        $quarterStartYear = null;
        $quarterEndMonth = null;
        $quarterEndYear = null;

        foreach ($events as $event):
            /** @var dvll\KirbyEvents\Models\EventPage $event */
            $dateTime = $event->getStartDate();
            $month = (int)$dateTime->format('n');
            $year = (int)$dateTime->format('Y');
            $quarter = (int)floor(($month - 1) / 3) + 1;
            $quarterKey = $year . '-' . $quarter;

            if ($quarterKey !== $currentQuarter):
                // Close previous quarter block
                if ($currentQuarter !== null): ?>
    </div>
<?php
                endif;

                $currentQuarter = $quarterKey;
                // Calculate start and end month/year for the quarter
                $quarterStartMonthNum = ($quarter - 1) * 3 + 1;
                $quarterEndMonthNum = $quarterStartMonthNum + 2;
                $quarterStartYear = $year;
                $quarterEndYear = $year;

                // Find all events in this quarter (from the paginated set)
                $quarterEvents = $events->filter(function ($e) use ($year, $quarter) {
                    $dateTime = $e->getStartDate();
                    $m = (int)$dateTime->format('n');
                    $y = (int)$dateTime->format('Y');
                    $q = (int)floor(($m - 1) / 3) + 1;
                    return $y === $year && $q === $quarter;
                });

                // Use the first and last event in the quarter for the headline
                /** @var dvll\KirbyEvents\Models\EventPage $firstEvent */
                $firstEvent = $quarterEvents->first();
                /** @var dvll\KirbyEvents\Models\EventPage $lastEvent */
                $lastEvent = $quarterEvents->last();

                $startMonthString = EventPage::getMonthString($firstEvent->getStartDate());
                $endMonthString = EventPage::getMonthString($lastEvent->getEndDateTime());

                $currentYear = (int)date('Y');
                $showYear = ($year > $currentYear);

                // Only show end month if different from start month
                $monthTitle = ($startMonthString === $endMonthString)
                    ? $startMonthString
                    : ($startMonthString . ' – ' . $endMonthString);
?>
<div class="dvll-block dvll-block--narrow">
    <h2 class="heading-h2 text-contrast">
        <?= $monthTitle ?><?= $showYear ? ' ' . $year : '' ?>
    </h2>
</div>
<div class="dvll-block dvll-block--wide grid grid-cols-(--dvll-card-grid-cols--small) gap-4 md:gap-6 md:justify-center auto-rows-fr">
<?php
            endif;
?>
<?= snippet('components/event-card', [
                'event' => $event,
                'buttonLabel' => 'Termindetails ansehen',
            ]) ?>
<?php endforeach ?>
</div>
<?php if ($events->pagination() && $events->pagination()->hasPages()): ?>
    <div class="dvll-block dvll-block--narrow">
        <?= snippet('components/pagination', [
            'pagination' => $events->pagination(),
        ]) ?>
    </div>
<?php endif ?>
</div>
</section>

<!-- Single shared event dialog for all event cards on the page -->
<?= snippet('components/event-dialog', ['showGoToOverviewButton' => false]) ?>

<?php endsnippet() ?>
