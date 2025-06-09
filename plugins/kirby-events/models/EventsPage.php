<?php

namespace dvll\KirbyEvents\Models;

use DateTime;
use Kirby\Toolkit\Str;
use Kirby\Uuid\Uuid;
use Pages;
use dvll\KirbyEvents\Services\GoogleCalendarService;
use dvll\KirbyEvents\Services\EventCategoryMatcher;
use dvll\KirbyEvents\Services\MockGoogleCalendarService;
use dvll\Sitepackage\Helpers\Helper;
use dvll\Sitepackage\Models\CustomBasePage;
use Kirby\Toolkit\A;

class EventsPage extends CustomBasePage
{
    public function children(): Pages
    {
        if ($this->children instanceof Pages) {
            return $this->children;
        }

        $cache      = kirby()->cache('dvll.kirby-events');
        $cacheKey   = 'events';
        $cacheTtl   = 60 * 24; // 24 hours in minutes

        $pages      = $cache->get($cacheKey);

        if (!$pages || Helper::getEnv('KIRBY_EVENTS_USE_MOCK', false)) {
            kirbylog('[dvll.kirby-events] Event cache empty. Fetching events from Google Calendar');
            $pages = self::fetchEventPagesFromCalendar();

            if (count($pages) === 0) {
                $cacheTtl = 15; // Reduce cache TTL to a shorter time if no events were found
            }
            kirbylog('[dvll.kirby-events] Fetched ' . count($pages) . ' new events from Google Calendar. Cache set for ' . $cacheTtl . ' minutes.');
            $cache->set($cacheKey, $pages, $cacheTtl);
        }

        return $this->children = Pages::factory($pages, $this);
    }

    /**
     * Fetches events from Google Calendar and formats them for Kirby Pages::factory
     * @return array<int, array<string, mixed>> Array of event page data
     */
    protected static function fetchEventPagesFromCalendar(): array
    {
        if (Helper::getEnv('KIRBY_EVENTS_USE_MOCK', false)) {
            $events = MockGoogleCalendarService::fetchEvents();
        } else {
            $events = GoogleCalendarService::fetchEvents();
        }
        $pages = [];
        foreach ($events as $key => $event) {
            if (!$event->summary && !$event->start) {
                continue; // Skip events without a summary
            }

            // Check for explicit category in description: [<category>]
            $explicitCategory = null;
            $description = $event->description ?? '';
            if (preg_match('/\[([^\]]+)\]/', $description, $matches)) {
                $possibleCategory = trim($matches[1]);
                if (array_key_exists($possibleCategory, \dvll\KirbyEvents\Models\EventEntity::$categoryWordMap)) {
                    $explicitCategory = $possibleCategory;
                    // Remove the first occurrence of [<category>] from the description
                    $description = preg_replace('/\[([^\]]+)\]/', '', $description, 1);
                    $description = trim($description);
                }
            }

            // Always perform matching for logging
            $matchedCategory = EventCategoryMatcher::detectCategory($event->summary ?? '', $description, \dvll\KirbyEvents\Models\EventEntity::$categoryWordMap);

            $slug = Str::slug(A::join([
                $event->start ? date('Y-m-d', strtotime($event->start)) : Str::substr($event->id, 0, 10),
                $event->summary
            ]));

            if ($matchedCategory === null) {
                kirbylog('[dvll.kirby-events] No matching category found for event: ' . $slug);
            } else if ($explicitCategory && $explicitCategory !== $matchedCategory) {
                kirbylog('[dvll.kirby-events] Explicit category MISMATCH: ' . $explicitCategory . ' vs ' . $matchedCategory . ' for event: ' . $slug);
            } elseif ($explicitCategory && $explicitCategory === $matchedCategory) {
                kirbylog('[dvll.kirby-events] Explicit category match: ' . $explicitCategory . ' for event: ' . $slug);
            } else {
                kirbylog('[dvll.kirby-events] Event category match: ' . $slug . ' => ' . $matchedCategory);
            }
            // Log the matching result

            $pages[] = [
                'slug'     => $slug,
                // 'num'      => $key + 1,
                'template' => 'event',
                'model'    => 'event',
                'content'  => [
                    'title'       => $event->summary,
                    'location'    => $event->location,
                    'description' => $description, // use possibly cleaned description
                    'start'       => $event->start,
                    'startDate'   => $event->startDate,
                    'end'         => $event->end,
                    'endDate'     => $event->endDate,
                    'url'         => $event->htmlLink,
                    'uuid'        => $event->id ? 'event-' . $event->id : Uuid::generate(),
                    'category'    => $explicitCategory ?? $matchedCategory, // Use explicit if present
                ]
            ];
        }
        return $pages;
    }

    /**
     * Returns both Google and Outlook calendar links for an event.
     * @param EventPage $event
     * @return array{google: string, outlook: string}
     */
    public function getCalendarLinks(EventPage $event): array
    {
        $title = $event->title();
        $description = $event->description()->isNotEmpty() ? $event->description()->escape() : '';
        $location = $event->location()->isNotEmpty() ? $event->location()->escape() : '';
        $allDay = $event->isAllDayEvent() ? true : false;
        $start = date('Y-m-d\TH:i:s', $event->getStartDate());
        $end = date('Y-m-d\TH:i:s', $event->getEndDate(useCorrection: false));

        $google = $this->buildGoogleCalendarUrl($title, $description, $location, $start, $end, $allDay);
        $outlook = $this->buildOutlookCalendarLink($title, $start, $end, $description, $location, $allDay);

        return [
            'google' => $google,
            'outlook' => $outlook,
        ];
    }

    private function buildGoogleCalendarUrl(string $title, string $description, string $location, string $start, string $end, bool $allDay = false): string
    {
        if ($allDay) {
            // All-day event: use date only (Ymd)
            $startUTC = (new DateTime($start))->format('Ymd');
            $endUTC = (new DateTime($end))->format('Ymd');
        } else {
            // Timed event
            $startUTC = (new DateTime($start))->format('Ymd\THis\Z');
            $endUTC = (new DateTime($end))->format('Ymd\THis\Z');
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

    private function buildOutlookCalendarLink(string $title, string $start, string $end, string $description = '', string $location = '', bool $allDay = false): string
    {
        if ($allDay) {
            // All-day event: use date only (Y-m-d) and add 'allday' param for Outlook
            $startUTC = (new DateTime($start))->format('Y-m-d');
            $endUTC = (new DateTime($end))->format('Y-m-d');
            $params = [
                'subject' => $title,
                'startdt' => $startUTC,
                'enddt' => $endUTC,
                'body' => $description,
                'location' => $location,
                'allday' => 'true', // Outlook recognizes this param
            ];
        } else {
            $startUTC = (new DateTime($start))
                ->format('Y-m-d\TH:i:s\Z');
            $endUTC = (new DateTime($end))
                ->format('Y-m-d\TH:i:s\Z');
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
