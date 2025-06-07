<?php

namespace dvll\KirbyEvents\Models;

use dvll\Sitepackage\Models\CustomBasePage;

class EventPage extends CustomBasePage
{

    public function showAsFullDayEventWithoutTime(): bool
    {
        /** @var \Kirby\Content\Field $startField */
        $startField = $this->content()->get('start');
        /** @var \Kirby\Content\Field $endField */
        $endField = $this->content()->get('end');

        $startDateFromDateTime = $startField->toDate('Y-m-d');
        $endDateFromDateTime = $endField->toDate('Y-m-d');

        return $this->content()->get('startDate')->isNotEmpty() || $startDateFromDateTime !== $endDateFromDateTime;
    }

    public function getStartDate(): int
    {
        /** @var \Kirby\Content\Field $startField */
        $startField = $this->content()->get('startDate')->isNotEmpty() ? $this->content()->get('startDate') : $this->content()->get('start');
        return $startField->toDate();
    }

    public function getEndDate(): int
    {
        // If all-day event, subtract one day from end date
        if (
            $this->content()->get('startDate')->isNotEmpty() &&
            $this->content()->get('endDate')->isNotEmpty()
        ) {
            // Subtract 1 day (86400 seconds)
            $endTimestamp = $this->content()->get('endDate')->toDate();
            return $endTimestamp - 86400;
        }

        /** @var \Kirby\Content\Field $endField */
        $endField = $this->content()->get('endDate')->isNotEmpty() ? $this->content()->get('endDate') : $this->content()->get('end');
        $endTimestamp = $endField->toDate();

        return $endTimestamp;
    }

    public function isAllDayEvent(): bool
    {
        /** @var \Kirby\Content\Field $startField */
        $startField = $this->content()->get('startDate');
        /** @var \Kirby\Content\Field $endField */
        $endField = $this->content()->get('endDate');

        return $startField->isNotEmpty() && $endField->isNotEmpty();
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

    public function getStartDateMonthString(): string
    {
        $monthStr = $this->getFormattedDateString($this->getStartDate());
        if (mb_strlen($monthStr) > 4) {
            return mb_substr($monthStr, 0, 3) . '.';
        }
        return $monthStr;
    }

    public function getEndDateMonthString(): string
    {
        $monthStr = $this->getFormattedDateString($this->getEndDate());
        if (mb_strlen($monthStr) > 4) {
            return mb_substr($monthStr, 0, 3) . '.';
        }
        return $monthStr;
    }

    private function getFormattedDateString(int|string|\DateTimeInterface $date, string $format = 'LLLL'): string
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

}
