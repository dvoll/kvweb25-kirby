<?php

/**
 * @var \Kirby\Cms\Site $site
 */

namespace dvll\Sitepackage\Models;

use dvll\Sitepackage\Helpers\UuidSelectFieldHelper;
use Kirby\Cms\Block;
use Kirby\Cms\Pages;

class TeaserEventsBlock extends Block
{
    /**
     * @return \Kirby\Cms\Pages
     */
    public function myEvents(): Pages
    {
        $eventsPage = kirby()->site()->find('termine');
        /** @var \Kirby\Content\Field $tagField */
        $tagField = $this->content()->get('tags');
        $source = $this->content()->get('source');

        if (!$eventsPage) {
            return Pages::factory([]);
        }

        if ($source->isEmpty() || $source->toString() === 'upcoming' || $tagField->isEmpty()) {
            // Return upcoming events (future events) sorted by start date
            return $eventsPage->children()
                ->unlisted()
                ->filter(function ($event) {
                    $startDate = $event->getStartDate();
                    return $startDate->getTimestamp() >= time();
                })
                ->sortBy('start', 'asc');
        }

        // Filter events by selected tags
        return $eventsPage->children()
            ->unlisted()
            ->filter(function ($event) use ($tagField) {
                /** @var \Kirby\Content\Field $eventCategoryField */
                $eventCategoryField = $event->content()->get('category');
                if ($eventCategoryField->isEmpty()) {
                    return false;
                }

                // Check if event's category matches any of the selected tags
                $selectedTags = $tagField->split();
                return in_array($eventCategoryField->value(), $selectedTags);
            })
            ->filter(function ($event) {
                // Only include future events
                $startDate = $event->getStartDate();
                return $startDate->getTimestamp() >= time();
            })
            ->sortBy('start', 'asc');
    }
}
