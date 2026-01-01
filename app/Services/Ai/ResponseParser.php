<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Log;

/**
 * Response Parser Service
 * 
 * Parses AI responses, validates structure, and processes duplicates.
 */
class ResponseParser
{
    /**
     * Parse bill analysis response from AI
     * 
     * Extracts JSON from response, validates structure, and processes items.
     * 
     * @param string $aiResponse
     * @return array
     * @throws AiException
     */
    public function parseBillAnalysis(string $aiResponse): array
    {
        // Try to extract JSON from the response
        $json = $this->extractJson($aiResponse);

        if (!$json) {
            throw new AiException('No valid JSON found in AI response');
        }

        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('JSON parse error', [
                'error' => json_last_error_msg(),
                'response' => $aiResponse,
            ]);
            throw new AiException('Failed to parse JSON: ' . json_last_error_msg());
        }

        // Validate structure
        if (!isset($data['items']) || !is_array($data['items'])) {
            throw new AiException('Invalid response structure: missing items array');
        }

        // Validate and normalize items
        $items = [];
        foreach ($data['items'] as $item) {
            $normalized = $this->normalizeItem($item);
            if ($normalized) {
                $items[] = $normalized;
            }
        }

        // Process duplicates and phantom billing
        $items = $this->processDuplicates($items);

        return [
            'hospital_name' => $data['hospital_name'] ?? null,
            'items' => $items,
        ];
    }

    /**
     * Extract JSON from text response
     * 
     * @param string $text
     * @return string|null
     */
    private function extractJson(string $text): ?string
    {
        // Try to find JSON in code blocks
        if (preg_match('/```(?:json)?\s*(\{.*?\})\s*```/s', $text, $matches)) {
            return $matches[1];
        }

        // Try to find JSON object directly
        if (preg_match('/\{.*\}/s', $text, $matches)) {
            return $matches[0];
        }

        return null;
    }

    /**
     * Normalize item data
     * 
     * @param array $item
     * @return array|null
     */
    private function normalizeItem(array $item): ?array
    {
        // Validate required fields
        if (empty($item['item_name']) || !isset($item['price'])) {
            return null;
        }

        // Normalize price
        $price = $this->normalizePrice($item['price']);

        // Validate status
        $status = $item['status'] ?? 'review';
        if (!in_array($status, ['danger', 'review', 'safe'])) {
            $status = 'review';
        }

        // Normalize service type (rawat_inap, rawat_jalan, or null)
        $serviceType = null;
        if (isset($item['service_type'])) {
            $serviceType = strtolower(trim($item['service_type']));
            if (!in_array($serviceType, ['rawat_inap', 'rawat_jalan'])) {
                $serviceType = null;
            }
        }

        return [
            'item_name' => trim($item['item_name']),
            'category' => trim($item['category'] ?? 'Lainnya'),
            'price' => $price,
            'status' => $status,
            'label' => trim($item['label'] ?? 'Perlu Ditinjau'),
            'description' => trim($item['description'] ?? ''),
            'service_type' => $serviceType,
        ];
    }

    /**
     * Normalize price value
     * 
     * @param mixed $price
     * @return float
     */
    private function normalizePrice($price): float
    {
        if (is_numeric($price)) {
            return (float) $price;
        }

        // Try to extract number from string (remove currency symbols, commas, etc.)
        $cleaned = preg_replace('/[^\d]/', '', (string) $price);
        
        if (empty($cleaned)) {
            return 0.0;
        }

        return (float) $cleaned;
    }

    /**
     * Normalize item name for duplicate detection
     * 
     * Removes extra spaces, punctuation, and normalizes the name
     * 
     * @param string $name
     * @return string
     */
    private function normalizeItemName(string $name): string
    {
        // Convert to lowercase
        $normalized = strtolower(trim($name));
        
        // Remove common punctuation and special characters
        $normalized = preg_replace('/[\/\-\.,;:()\[\]{}]/', ' ', $normalized);
        
        // Remove multiple spaces
        $normalized = preg_replace('/\s+/', ' ', $normalized);
        
        // Trim again
        $normalized = trim($normalized);
        
        return $normalized;
    }

    /**
     * Process duplicates and detect phantom billing
     * 
     * Items with same name and same price are flagged as phantom billing.
     * Items with same name but different price are deduplicated.
     * 
     * @param array $items
     * @return array
     */
    private function processDuplicates(array $items): array
    {
        $processed = [];
        $seenByName = []; // Track items by normalized name only (for different prices)
        $seenByExact = []; // Track items by normalized name+price (for exact duplicates)
        $duplicateGroups = []; // Track all items with same name+price for marking

        // First pass: collect all items and group duplicates
        foreach ($items as $item) {
            $normalizedName = $this->normalizeItemName($item['item_name']);
            $itemPrice = (float) $item['price'];
            
            // Create a key for exact duplicate detection (normalized name + price)
            $exactKey = $normalizedName . '|' . $itemPrice;
            
            // Track duplicate groups
            if (!isset($duplicateGroups[$exactKey])) {
                $duplicateGroups[$exactKey] = [];
            }
            $duplicateGroups[$exactKey][] = count($processed); // Store index
            
            // Store normalized name for reference
            $item['_normalized_name'] = $normalizedName;
            $processed[] = $item;
        }

        // Second pass: mark duplicates as danger
        foreach ($duplicateGroups as $exactKey => $indices) {
            // If there are 2 or more items with same name and price, mark all as danger
            if (count($indices) >= 2) {
                foreach ($indices as $idx) {
                    $processed[$idx]['status'] = 'danger';
                    $processed[$idx]['label'] = 'Potensi Phantom Billing';
                    $processed[$idx]['description'] = 'Item duplikat dengan nama dan harga yang sama persis ditemukan ' . 
                        count($indices) . ' kali dalam tagihan. ' . 
                        ($processed[$idx]['description'] ?? '');
                }
            }
        }

        // Third pass: handle same name but different price (keep first occurrence)
        $finalProcessed = [];
        $seenByName = [];
        
        foreach ($processed as $item) {
            $normalizedName = $item['_normalized_name'];
            $itemPrice = (float) $item['price'];
            $exactKey = $normalizedName . '|' . $itemPrice;
            
            // If exact duplicate (same name + same price), always include all (already marked as danger)
            if (isset($duplicateGroups[$exactKey]) && count($duplicateGroups[$exactKey]) >= 2) {
                unset($item['_normalized_name']); // Remove temporary field
                $finalProcessed[] = $item;
                continue;
            }
            
            // If same normalized name but different price, only keep first occurrence
            if (isset($seenByName[$normalizedName])) {
                // Skip this duplicate (keep the first one with different price)
                continue;
            }
            
            // Mark as seen and add to final list
            $seenByName[$normalizedName] = true;
            unset($item['_normalized_name']); // Remove temporary field
            $finalProcessed[] = $item;
        }

        return $finalProcessed;
    }
}
