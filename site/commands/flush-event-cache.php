<?php

use Kirby\CLI\CLI;
use Kirby\Cms\Page;

return [
    'description' => 'Flush Google Calendar events cache',
    'args' => [],
    'command' => static function (CLI $cli): void {
        kirby()->impersonate('kirby');
        $cli->info('Flushing Google Calendar events cache...');

        $eventCache = kirby()->cache('dvll.kirby-events');
        $flushSuccess = $eventCache->flush();

        if ($flushSuccess) {
            $cli->info('Event cache flushed successfully.');
        } else {
            $cli->error('Failed to flush event cache.');
        }
    }
];
