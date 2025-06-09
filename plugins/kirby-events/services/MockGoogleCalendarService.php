<?php
namespace dvll\KirbyEvents\Services;

use dvll\KirbyEvents\Models\EventEntity;

class MockGoogleCalendarService
{
    /**
     * Returns mock Google Calendar events for testing with variations.
     * @param array<string, mixed> $config
     * @param string|null $calendarId
     * @param int $minResults
     * @return EventEntity[]
     */
    public static function fetchEvents(array $config = [], ?string $calendarId = null, int $minResults = 20): array
    {
        $mockEvents = [];
        $nextYear = date('Y') + 1;
        $htmlLink = "https://calendar.google.com/event?eid=mock";

        // German event texts with category keywords
        $categories = [
            [
                'summary' => 'Spendenaktion für den Verein',
                'description' => 'Wir sammeln Spenden und bitten um Unterstützung für unser neues Projekt.'
            ],
            [
                'summary' => 'Mädchenzeltlager Sommer 2024',
                'description' => 'Das Zeltlager für Mädchen bietet viele Aktivitäten und gemeinsames Zelten.'
            ],
            [
                'summary' => 'Oldie Zeltlager Rückblick',
                'description' => 'Ältere Teilnehmer berichten von ihren Erfahrungen im Oldie Zeltlager.'
            ],
            [
                'summary' => 'Familien Zeltlager Anmeldung',
                'description' => 'Familien und Kinder sind herzlich eingeladen zum Familien Zeltlager.'
            ],
            [
                'summary' => 'Sportliches Turnier',
                'description' => 'Das große Sportturnier mit spannenden Spielen und viel Bewegung.'
            ],
            [
                'summary' => 'Posaunenchor Konzert',
                'description' => 'Der Posaunenchor lädt zum musikalischen Konzert ein.'
            ],
            [
                'summary' => 'Klaeks Kindergruppe',
                'description' => 'Die Klaeks Gruppe bietet ein buntes Angebot für Kinder und Jugendliche.'
            ],
            [
                'summary' => 'Gruppenangebot: Kreativkurs',
                'description' => 'Unser neues Gruppenangebot: Ein Kurs für kreative Aktivitäten.'
            ],
            [
                'summary' => 'Kreisverband Sitzung',
                'description' => 'Die Organisation des Kreisverbands trifft sich zur jährlichen Sitzung.'
            ],
            [
                'summary' => 'Vorstandssitzung',
                'description' => 'Das Gremium Vorstand bespricht die Leitung und Führung des Vereins.'
            ],
            [
                'summary' => 'Ortsvereine Treffen',
                'description' => 'Die Ortsvereine und Gemeinden laden zum lokalen Austausch ein.'
            ],
            [
                'summary' => 'Chronik Rückblick',
                'description' => 'Ein Rückblick auf die Geschichte und Chronik des letzten Jahres.'
            ],
            [
                'summary' => 'Schutzkonzept Workshop',
                'description' => 'Prävention und Schutz stehen im Mittelpunkt unseres Schutzkonzepts.'
            ],
            [
                'summary' => 'Kontakt und Anfrage',
                'description' => 'Für Anfragen und Kontakt stehen wir jederzeit erreichbar zur Verfügung.'
            ],
        ];

        // Add one event for each category above (timed or all-day alternately)
        foreach ($categories as $idx => $cat) {
            $month = str_pad(strval((($idx) % 12) + 1), 2, '0', STR_PAD_LEFT);
            $day = str_pad(strval((($idx) % 28) + 1), 2, '0', STR_PAD_LEFT);
            if ($idx % 3 === 0) {
                // Multi-day all-day event
                $startDate = "$nextYear-$month-$day";
                $endDate = date('Y-m-d', strtotime("$startDate +3 day"));
                $mockEvents[] = new EventEntity([
                    'id' => "mock-event-" . ($idx + 1),
                    'summary' => $cat['summary'],
                    'description' => $cat['description'],
                    'location' => "Ort $idx",
                    'htmlLink' => $htmlLink,
                    'start' => null,
                    'startDate' => $startDate,
                    'end' => null,
                    'endDate' => $endDate,
                ]);
            } elseif ($idx % 3 === 1) {
                // All-day event
                $startDate = "$nextYear-$month-$day";
                $endDate = date('Y-m-d', strtotime("$startDate +1 day"));
                $mockEvents[] = new EventEntity([
                    'id' => "mock-event-" . ($idx + 1),
                    'summary' => $cat['summary'],
                    'description' => $cat['description'],
                    'location' => "Ort $idx",
                    'htmlLink' => $htmlLink,
                    'start' => null,
                    'startDate' => $startDate,
                    'end' => null,
                    'endDate' => $endDate,
                ]);
            } else {
                // Timed event
                $start = "$nextYear-$month-$day" . "T09:00:00+00:00";
                $end = "$nextYear-$month-$day" . "T10:00:00+00:00";
                $mockEvents[] = new EventEntity([
                    'id' => "mock-event-" . ($idx + 1),
                    'summary' => $cat['summary'],
                    'description' => $cat['description'],
                    'location' => "Ort $idx",
                    'htmlLink' => $htmlLink,
                    'start' => $start,
                    'startDate' => null,
                    'end' => $end,
                    'endDate' => null,
                ]);
            }
        }

        // Add field length and missing/null/empty variations
        $variationCount = 6;
        for ($v = 0; $v < $variationCount; $v++) {
            $month = str_pad(strval((($v + 20) % 12) + 1), 2, '0', STR_PAD_LEFT);
            $day = str_pad(strval((($v + 20) % 28) + 1), 2, '0', STR_PAD_LEFT);
            $id = "mock-var-" . ($v + 1);

            if ($v === 0) {
                // Short fields
                $summary = "Kurz";
                $description = "Kurz";
                $location = "A";
            } elseif ($v === 1) {
                // Medium fields
                $summary = "Mittellanger Titel";
                $description = "Dies ist eine mittellange Beschreibung für einen Testevent.";
                $location = "Musterstadt";
            } elseif ($v === 2) {
                // Long fields
                $summary = str_repeat("Langer Titel ", 5);
                $description = str_repeat("Lange Beschreibung. ", 10);
                $location = str_repeat("Langer Ort ", 5);
            } elseif ($v === 3) {
                // Null location
                $summary = "Kein Ort";
                $description = "Dies ist ein Event ohne Ortsangabe.";
                $location = null;
            } else {
                // Very long fields
                $summary = str_repeat("Sehr langer Titel ", 10);
                $description = str_repeat("Sehr lange Beschreibung. ", 20);
                $location = str_repeat("Sehr langer Ort", 10);
            }

            // Alternate timed/all-day, add multi-day all-day for $v === 2
            if ($v % 3 === 0) {
                // Multi-day all-day event
                $startDate = "$nextYear-$month-$day";
                $endDate = date('Y-m-d', strtotime("$startDate +3 day"));
                $mockEvents[] = new EventEntity([
                    'id' => $id,
                    'summary' => $summary,
                    'description' => $description,
                    'location' => $location,
                    'htmlLink' => $htmlLink,
                    'start' => null,
                    'startDate' => $startDate,
                    'end' => null,
                    'endDate' => $endDate,
                ]);
            } elseif ($v % 3 === 1) {
                // All-day event (single day)
                $startDate = "$nextYear-$month-$day";
                $endDate = date('Y-m-d', strtotime("$startDate +1 day"));
                $mockEvents[] = new EventEntity([
                    'id' => $id,
                    'summary' => $summary,
                    'description' => $description,
                    'location' => $location,
                    'htmlLink' => $htmlLink,
                    'start' => null,
                    'startDate' => $startDate,
                    'end' => null,
                    'endDate' => $endDate,
                ]);
            } else {
                // Timed event
                $start = "$nextYear-$month-$day" . "T09:00:00+00:00";
                $end = "$nextYear-$month-$day" . "T10:00:00+00:00";
                $mockEvents[] = new EventEntity([
                    'id' => $id,
                    'summary' => $summary,
                    'description' => $description,
                    'location' => $location,
                    'htmlLink' => $htmlLink,
                    'start' => $start,
                    'startDate' => null,
                    'end' => $end,
                    'endDate' => null,
                ]);
            }
        }

        // Fill up to $maxResults with generic events, reusing the above texts
        for ($i = count($mockEvents) + 1; false && $i <= $minResults; $i++) {
            $catIdx = ($i - 1) % count($categories);
            $summary = $categories[$catIdx]['summary'] . " $i";
            $description = $categories[$catIdx]['description'] . " (Event $i)";
            $location = "Ort $i";
            $month = str_pad(strval((($i - 1) % 12) + 1), 2, '0', STR_PAD_LEFT);
            $day = str_pad(strval((($i - 1) % 28) + 1), 2, '0', STR_PAD_LEFT);

            if ($i % 2 === 0) {
                $startDate = "$nextYear-$month-$day";
                $endDate = date('Y-m-d', strtotime("$startDate +1 day"));
                $mockEvents[] = new EventEntity([
                    'id' => "mock-event-$i",
                    'summary' => $summary,
                    'description' => $description,
                    'location' => $location,
                    'htmlLink' => $htmlLink,
                    'start' => null,
                    'startDate' => $startDate,
                    'end' => null,
                    'endDate' => $endDate,
                ]);
            } else {
                $start = "$nextYear-$month-$day" . "T09:00:00+00:00";
                $end = "$nextYear-$month-$day" . "T10:00:00+00:00";
                $mockEvents[] = new EventEntity([
                    'id' => "mock-event-$i",
                    'summary' => $summary,
                    'description' => $description,
                    'location' => $location,
                    'htmlLink' => $htmlLink,
                    'start' => $start,
                    'startDate' => null,
                    'end' => $end,
                    'endDate' => null,
                ]);
            }
        }

        return $mockEvents;
    }
}
