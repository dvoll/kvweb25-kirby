<?php
namespace dvll\KirbyEvents\Services;

use Google\Client;
use Google\Service\Calendar;
use dvll\Sitepackage\Helpers\Helper;

class GoogleCalendarService
{
    /**
     * Fetches Google Calendar events using a service account and returns them as EventEntity objects.
     * @param array<string, mixed> $config
     * @param string|null $calendarId
     * @param int $maxResults
     * @return \dvll\KirbyEvents\Models\EventEntity[]
     */
    public static function fetchEvents(array $config = [], ?string $calendarId = null, int $maxResults = 20): array
    {
        $events = [];
        $calendarId = $calendarId ?? (\dvll\Sitepackage\Helpers\Helper::getEnv('GOOGLE_CALENDAR_ID', 'primary'));
        $client = new Client();
        $client->setAuthConfig([
            'type' => 'service_account',
            'project_id' => $config['project_id'] ?? Helper::getEnv('GOOGLE_API_PROJECT_ID'),
            'private_key_id' => $config['private_key_id'] ?? Helper::getEnv('GOOGLE_API_PRIVATE_KEY_ID'),
            'private_key' => str_replace('\\n', "\n", $config['private_key'] ?? Helper::getEnv('GOOGLE_API_PRIVATE_KEY', '')),
            'client_email' => $config['client_email'] ?? Helper::getEnv('GOOGLE_API_CLIENT_EMAIL'),
            'client_id' => $config['client_id'] ?? Helper::getEnv('GOOGLE_API_CLIENT_ID'),
            'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
            'token_uri' => 'https://oauth2.googleapis.com/token',
            'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
            'client_x509_cert_url' => 'https://www.googleapis.com/robot/v1/metadata/x509/',
            'universe_domain' => 'googleapis.com',
        ]);
        $client->setScopes(['https://www.googleapis.com/auth/calendar.readonly']);
        if (!empty($config['subject'])) {
            $client->setSubject($config['subject']);
        }

        $service = new Calendar($client);
        $optParams = [
            'maxResults' => $maxResults,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => date('c'),
        ];
        try {
            $results = $service->events->listEvents($calendarId, $optParams);
            foreach ($results->getItems() as $item) {
                // Robustly extract start and end (all-day or timed)
                $startObj = $item->getStart();
                $endObj = $item->getEnd();

                $start = null;
                if ($startObj) {
                    $start = $startObj->getDateTime() ?: $startObj->getDate() ?: null;
                }

                $end = null;
                if ($endObj) {
                    $end = $endObj->getDateTime() ?: $endObj->getDate() ?: null;
                }

                $events[] = new \dvll\KirbyEvents\Models\EventEntity([
                    'id' => $item->getId() ?? null,
                    'summary' => $item->getSummary() ?? '',
                    'description' => $item->getDescription() ?? '',
                    'location' => $item->getLocation() ?? '',
                    'htmlLink' => $item->getHtmlLink() ?? '',
                    'start' => $start,
                    'startDate' => $startObj && $startObj->getDate() ? $startObj->getDate() : null,
                    'end' => $end,
                    'endDate' => $endObj ? $endObj->getDate() : null,
                ]);
            }
        } catch (\Exception $e) {
            kirbylog('[dvll.kirby-events] Error fetching events from Google Calendar: ' . $e->getMessage(), Helper::KIRBYLOG_LVL_ERROR);
            return [];
        }
        return $events;
    }
}
