<?php

/**
 * @var dvll\KirbyEvents\Models\EventPage $page
 * @var Kirby\Cms\Site $site
 * @var Kirby\Cms\App $kirby
 */

$kirby->response()->type('text/calendar');


function iCalDateFormat(int $uStamp = 0, bool $allDay = false): string
{
    $date = new DateTime();
    $date->setTimestamp($uStamp);
    if ($allDay) {
        // All-day events: only date part
        return $date->format('Ymd');
    }
    // Timed events: date-time part
    return $date->format('Ymd\THis');
}

function iCalTextFormat(string $input): string
{
    return str_replace("\n", '\n', $input);
}

$startDate = $page->getStartDate();
$endDate = $page->getEndDate(useCorrection: false);
// Assume $page->isAllDay() returns true for all-day events
$isAllDay = $page->isAllDayEvent();

?>
BEGIN:VCALENDAR<?= PHP_EOL ?>
VERSION:2.0<?= PHP_EOL ?>
PRODID:<?= $site->title() ?><?= PHP_EOL ?>
METHOD:PUBLISH<?= PHP_EOL ?>
BEGIN:VEVENT<?= PHP_EOL ?>
<?php if ($isAllDay): ?>
DTSTART;VALUE=DATE:<?= iCalDateFormat($startDate, true) ?><?= PHP_EOL ?>
DTEND;VALUE=DATE:<?= iCalDateFormat($endDate, true) ?><?= PHP_EOL ?>
<?php else: ?>
DTSTART:<?= iCalDateFormat($startDate) ?><?= PHP_EOL ?>
DTEND:<?= iCalDateFormat($endDate) ?><?= PHP_EOL ?>
<?php endif; ?>
SUMMARY:<?= $page->title() ?><?= PHP_EOL ?>
DESCRIPTION:<?= iCalTextFormat($page->description()->escape()) ?><?= PHP_EOL ?>
LOCATION:<?= iCalTextFormat($page->location()->escape()) ?><?= PHP_EOL ?>
END:VEVENT<?= PHP_EOL ?>
END:VCALENDAR
