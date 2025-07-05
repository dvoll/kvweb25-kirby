<?php

namespace dvll\KirbyEvents\Models;

use dvll\Sitepackage\Models\CustomBasePage;

class EventPage extends CustomBasePage
{

    public function getTag() {
        /** @var Kirby\Content\Field $eventCategory */
        $eventCategory = $this->content()->get('category');
        $eventMatchingTag = site()->tags()->toStructure()->findBy('customuuid', $eventCategory->value());
        return $eventMatchingTag;
    }
    public function getTagPage(): ?\Kirby\Cms\Page {
        $eventMatchingTag = $this->getTag();
        return ($eventMatchingTag && $eventMatchingTag->isNotEmpty()) ? $eventMatchingTag->page()->toPage() : null;
    }

    public function getStartDate(): \DateTimeImmutable
    {
        /** @var \Kirby\Content\Field $startField */
        $startField = $this->content()->get('start');
        $value = $startField->toString();

        // Always treat as UTC and convert to Europe/Berlin
        try {
            $dt = new \DateTimeImmutable($value, new \DateTimeZone('UTC'));
            $dtBerlin = $dt->setTimezone(new \DateTimeZone('Europe/Berlin'));
            return $dtBerlin;
        } catch (\Exception $e) {
            // fallback to default
            $timestamp = $startField->toDate();
            return new \DateTimeImmutable('@' . $timestamp);
        }
    }

    public function getBackendTitle(): string
    {
        return $this->getStartDate()->format('d.m.Y') . ' | ' . $this->title();
    }

    public function getEndDateTime(): \DateTimeImmutable
    {
        /** @var \Kirby\Content\Field $endField */
        $endField = $this->content()->get('end');
        $value = $endField->toString();

        // Always treat as UTC and convert to Europe/Berlin
        try {
            $dt = new \DateTimeImmutable($value, new \DateTimeZone('UTC'));
            $dtBerlin = $dt->setTimezone(new \DateTimeZone('Europe/Berlin'));
            return $dtBerlin;
        } catch (\Exception $e) {
            // fallback to default
            $timestamp = $endField->toDate();
            return new \DateTimeImmutable('@' . $timestamp);
        }
    }

    public function getEndDateOnly(bool $useCorrection = true): int
    {
        /** @var \Kirby\Content\Field $endDate */
        $endDate = $this->content()->get('endDate');

        // If all-day event and only one day, subtract one day from end date
        if ($useCorrection && $endDate->isNotEmpty()) {
            $endTimestamp = $endDate->toDate();
            return $endTimestamp - 86400;
        }

        if ($endDate->isNotEmpty()) {
            return $endDate->toDate();
        }

        return $this->getEndDateTime()->getTimestamp();
    }

    public function isAllDayEvent(): bool
    {
        /** @var \Kirby\Content\Field $allDayField */
        $allDayField = $this->content()->get('isAllDay');

        return $allDayField->isNotEmpty() && $allDayField->toBool();
    }

    public function hasMultipleDays(): bool
    {
        if ($this->isAllDayEvent()) {
            // All-day event: endDate is exclusive, so subtract one day for comparison
            $start = $this->content()->get('startDate');
            $end = $this->content()->get('endDate');
            $startDate = new \DateTime($start);
            $endDate = new \DateTime($end);
            $endDate->modify('-1 day');
            return $startDate->format('Y-m-d') !== $endDate->format('Y-m-d');
        }

        /** @var \Kirby\Content\Field $startField */
        $startField = $this->content()->get('start');
        /** @var \Kirby\Content\Field $endField */
        $endField = $this->content()->get('end');

        // Default: compare as before
        return $startField->toDate('Y-m-d') !== $endField->toDate('Y-m-d');
    }


    public function getConnectedBlogposts(): ?\Kirby\Cms\Pages
    {
        $blogPosts = $this->site()->find('blog')->children()->listed()->filter(fn($page) => $page->event()->isNotEmpty() &&  array_find_key($page->event()->yaml(), function ($blogpostEventId) {
            return $blogpostEventId === $this->uuid()->toString();
        }) !== null );
        return $blogPosts;
    }

    public static function getMonthString(int|\DateTimeInterface $date, bool $cut = false): string
    {
        $monthStr = self::getFormattedDateString($date);
        if ($cut && mb_strlen($monthStr) > 4) {
            return mb_substr($monthStr, 0, 3) . '.';
        }
        return $monthStr;
    }

    public static function getDateWeekdayString(int|\DateTimeInterface $date): string
    {
        return self::getFormattedDateString($date, 'EEEE');
    }

    private static function getFormattedDateString(int|string|\DateTimeInterface $date, string $format = 'LLLL'): string
    {
        $dateFormatter = new \IntlDateFormatter(
            'de_DE',
            \IntlDateFormatter::NONE,
            \IntlDateFormatter::NONE,
            null,
            null,
            $format
        );
        return $dateFormatter->format($date);
    }

    /**
     * Returns both Google and Outlook calendar links for an event.
     * @param \dvll\KirbyEvents\Models\EventPage $event
     * @return array{google: string, outlook: string}
     */
    public static function getCalendarLinks(EventPage $event): array
    {
        /** @var \Kirby\Content\Field $descriptionField */
        $descriptionField = $event->content()->get('description');
        /** @var \Kirby\Content\Field $locationField */
        $locationField = $event->content()->get('location');

        $title = $event->title();
        $description = $descriptionField->isNotEmpty() ? $descriptionField->escape() : '';
        $location = $locationField->isNotEmpty() ? $locationField->escape() : '';
        $allDay = $event->isAllDayEvent() ? true : false;
        $start = $event->getStartDate();
        $end = $event->getEndDateTime();

        $google = self::buildGoogleCalendarUrl($title, $description, $location, $start, $end, $allDay);
        $outlook = self::buildOutlookCalendarLink($title, $start, $end, $description, $location, $allDay);

        return [
            'google' => $google,
            'outlook' => $outlook,
        ];
    }

    private static function buildGoogleCalendarUrl(string $title, string $description, string $location, \DateTimeImmutable $start, \DateTimeImmutable $end, bool $allDay = false): string
    {
        if ($allDay) {
            // All-day event: use date only (Ymd)
            $startUTC = $start->format('Ymd');
            $endUTC = $end->format('Ymd');
        } else {
            // Timed event
            $startUTC = $start->format('Ymd\THis\Z');
            $endUTC = $end->format('Ymd\THis\Z');
        }

        // Build URL
        $params = http_build_query([
            'action' => 'TEMPLATE',
            'text' => $title,
            'dates' => "{$startUTC}/{$endUTC}",
            'details' => $description,
            'location' => $location,
        ]);

        return "https://calendar.google.com/calendar/render?$params";
    }

    private static function buildOutlookCalendarLink(string $title, \DateTimeImmutable $start, \DateTimeImmutable $end, string $description = '', string $location = '', bool $allDay = false): string
    {
        if ($allDay) {
            // All-day event: use date only (Y-m-d) and add 'allday' param for Outlook
            $startUTC = $start->format('Y-m-d');
            $endUTC = $end->format('Y-m-d');
            $params = [
                'subject' => $title,
                'startdt' => $startUTC,
                'enddt' => $endUTC,
                'body' => $description,
                'location' => $location,
                'allday' => 'true', // Outlook recognizes this param
            ];
        } else {
            $startUTC = $start->format('Y-m-d\TH:i:s\Z');
            $endUTC = $end->format('Y-m-d\TH:i:s\Z');
            $params = [
                'subject' => $title,
                'startdt' => $startUTC,
                'enddt' => $endUTC,
                'body' => $description,
                'location' => $location,
            ];
        }

        // Build URL
        $query = http_build_query($params);

        return "https://outlook.live.com/calendar/0/deeplink/compose?$query";
    }
}
