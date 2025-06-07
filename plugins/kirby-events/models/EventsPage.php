<?php

namespace dvll\KirbyEvents\Models;

use Kirby\Toolkit\Str;
use Kirby\Uuid\Uuid;
use Pages;
use dvll\KirbyEvents\Services\GoogleCalendarService;
use dvll\KirbyEvents\Services\EventCategoryMatcher;
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

        if (!$pages) {
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
        $events = GoogleCalendarService::fetchEvents();
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
}
