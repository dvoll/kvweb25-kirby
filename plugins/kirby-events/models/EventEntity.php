<?php
namespace dvll\KirbyEvents\Models;

class EventEntity
{
    public string $id;
    public string $summary;
    public ?string $description;
    public ?string $location;
    public ?string $htmlLink;
    public ?string $start;
    public ?string $end;

    /**
     * @var array<string, array<string, int>>
     * Example: 'category' => ['word' => weight, ...]
     */
    public static array $categoryWordMap = [
        'Spenden' => [
            'spende' => 10,
            'spenden' => 10,
            'unterstützen' => 7,
            'hilfe' => 5,
            'förderung' => 5,
            'beitrag' => 4,
            'sammeln' => 4,
        ],
        'Zeltlager' => [
            'mädchenzeltlager' => 10,
            'jungenzeltlager' => 10,
            'zeltlager' => 8,
            'lager' => 7,
            'camp' => 6,
            'zelten' => 5,
            'jungschar' => 5,
        ],
        'Oldie Zeltlager' => [
            'oldie zeltlager' => 10,
            'oldie' => 10,
            'zeltlager' => 5,
            'ältere' => 4,
        ],
        'Familien Zeltlager' => [
            'familien zeltlager' => 10,
            'familie' => 8,
            'eltern' => 6,
            'kinder' => 6,
            'zeltlager' => 5,
        ],
        'Weitere Arbeit' => [
            'arbeit' => 8,
            'projekt' => 6,
            'mitarbeit' => 7,
            'helfen' => 5,
            'einsatz' => 5,
            'engagement' => 6,
        ],
        'Sport' => [
            'sport' => 10,
            'turnier' => 8,
            'spiel' => 7,
            'training' => 7,
            'mannschaft' => 6,
            'wettkampf' => 6,
            'bewegung' => 5,
        ],
        'Posaunen' => [
            'posaunen' => 10,
            'posaunenchor' => 10,
            'musik' => 6,
            'bläser' => 8,
            'konzert' => 7,
            'probe' => 5,
        ],
        'Klaeks' => [
            'klaeks' => 10,
            'kinder' => 6,
            'jugend' => 5,
            'gruppe' => 5,
            'angebot' => 4,
        ],
        'Gruppenangebote' => [
            'gruppe' => 10,
            'angebot' => 8,
            'treffen' => 7,
            'veranstaltung' => 6,
            'aktivität' => 6,
            'kurs' => 5,
        ],
        'Der Kreisverband' => [
            'kreisverband' => 10,
            'verband' => 8,
            'kreis' => 7,
            'organisation' => 6,
            'struktur' => 5,
        ],
        'Vorstand' => [
            'vorstand' => 10,
            'leitung' => 8,
            'vorsitz' => 7,
            'gremium' => 6,
            'führung' => 5,
        ],
        'Ortsvereine' => [
            'ortsverein' => 10,
            'verein' => 8,
            'ortsvereine' => 10,
            'gemeinde' => 7,
            'lokal' => 6,
        ],
        'Chronik' => [
            'chronik' => 10,
            'geschichte' => 8,
            'historie' => 7,
            'rückblick' => 6,
            'jahr' => 5,
        ],
        'Schutzkonzept' => [
            'schutzkonzept' => 10,
            'schutz' => 8,
            'prävention' => 8,
        ],
        'Kontakt' => [
            'kontakt' => 10,
            'anfrage' => 8,
            'erreichbar' => 5,
        ],
    ];

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? '';
        $this->summary = $data['summary'] ?? '';
        $this->description = $data['description'] ?? null;
        $this->location = $data['location'] ?? null;
        $this->htmlLink = $data['htmlLink'] ?? null;
        $this->start = $data['start'] ?? null;
        $this->end = $data['end'] ?? null;
    }
}
