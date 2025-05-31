<?php
namespace dvll\KirbyEvents\Services;

class EventCategoryMatcher
{

    /**
     * Detects the category for a given summary and description.
     *
     * @param string $summary
     * @param string $description
     * @param array<string, array<string, int>> $categoryWordMap
     * @return string|null
     */
    public static function detectCategory(string $summary, string $description, array $categoryWordMap): ?string
    {
        $haystack = strtolower($summary . ' ' . $description);
        $tokens = preg_split('/\W+/', $haystack, -1, PREG_SPLIT_NO_EMPTY);

        $categoryScores = [];

        // Build a lookup for all keywords to their categories and weights
        $keywordMap = [];
        foreach ($categoryWordMap as $cat => $words) {
            foreach ($words as $word => $weight) {
                $keywordMap[$word][$cat] = $weight;
            }
        }

        // For each token in the text, check against all keywords
        foreach ($tokens as $token) {
            foreach ($keywordMap as $keyword => $catWeights) {
                // Exact match (case-insensitive)
                if (strcasecmp($token, $keyword) === 0) {
                    foreach ($catWeights as $cat => $weight) {
                        $categoryScores[$cat] = ($categoryScores[$cat] ?? 0) + $weight;
                    }
                    continue;
                }
                // Fuzzy match
                similar_text($keyword, $token, $percent);
                if ($percent > 85) { // threshold can be adjusted
                    foreach ($catWeights as $cat => $weight) {
                        $categoryScores[$cat] = ($categoryScores[$cat] ?? 0) + $weight;
                    }
                }
            }
        }

        // Also check for multi-word keywords/phrases in the haystack
        foreach ($keywordMap as $keyword => $catWeights) {
            if (strpos($keyword, ' ') !== false && stripos($haystack, $keyword) !== false) {
                foreach ($catWeights as $cat => $weight) {
                    $categoryScores[$cat] = ($categoryScores[$cat] ?? 0) + $weight;
                }
            }
        }

        // Pick the category with the highest score
        if (!empty($categoryScores)) {
            arsort($categoryScores);
            $topCategories = array_slice($categoryScores, 0, 3, true);
            $logParts = [];
            foreach ($topCategories as $cat => $score) {
                $logParts[] = "$cat:$score";
            }
            $matchedCategory = array_key_first($categoryScores);
            kirbylog('[dvll.kirby-events] Detected category for event "' . $summary . '": ' . $matchedCategory . ' (Top 3: ' . implode(', ', $logParts) . ')');
            return $matchedCategory;
        }
        return null;
    }
}
