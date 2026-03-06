<?php

namespace App\Rules;

use App\Models\Vehicle\Vehicle;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniquePlateNumber implements ValidationRule
{
    /**
     * The vehicle ID to exclude from the check (for updates).
     */
    protected ?string $excludeVehicleId;

    /**
     * The owner ID to scope the check to (optional).
     */
    protected ?string $ownerId;

    /**
     * Create a new rule instance.
     *
     * @param string|null $excludeVehicleId Vehicle ID to exclude (for updates)
     * @param string|null $ownerId Owner ID to scope check (null = check all vehicles)
     */
    public function __construct(?string $excludeVehicleId = null, ?string $ownerId = null)
    {
        $this->excludeVehicleId = $excludeVehicleId;
        $this->ownerId = $ownerId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }

        $normalizedInput = $this->normalizePlateNumber($value);

        // Build query to check for duplicate plates
        $query = Vehicle::query();

        // Exclude current vehicle if updating
        if ($this->excludeVehicleId) {
            $query->where('id', '!=', $this->excludeVehicleId);
        }

        // Get all vehicles and check for normalized matches
        $existingVehicles = $query->pluck('plate_number', 'id');

        foreach ($existingVehicles as $vehicleId => $existingPlate) {
            $normalizedExisting = $this->normalizePlateNumber($existingPlate);

            if ($normalizedInput === $normalizedExisting) {
                $fail("A vehicle with a similar plate number already exists: {$existingPlate}. Please verify this is not a duplicate.");
                return;
            }
        }
    }

    /**
     * Normalize a plate number for comparison.
     * Removes all non-alphanumeric characters and converts to uppercase.
     *
     * Examples:
     * - "717-TPX" -> "717TPX"
     * - "717 TPX" -> "717TPX"
     * - "717TPX" -> "717TPX"
     * - "ABC 123 XY" -> "ABC123XY"
     * - "ABC-123-XY" -> "ABC123XY"
     *
     * @param string $plateNumber
     * @return string
     */
    public static function normalizePlateNumber(string $plateNumber): string
    {
        // Remove all non-alphanumeric characters (spaces, hyphens, dots, etc.)
        $normalized = preg_replace('/[^A-Za-z0-9]/', '', $plateNumber);

        // Convert to uppercase for case-insensitive comparison
        return strtoupper($normalized);
    }

    /**
     * Generate all possible format variations of a plate number for display.
     *
     * @param string $plateNumber
     * @return array
     */
    public static function getPlateVariations(string $plateNumber): array
    {
        $normalized = self::normalizePlateNumber($plateNumber);
        $variations = [$normalized];

        // Try to detect common patterns and generate variations
        // Pattern: 3 digits + 3 letters (e.g., 717TPX)
        if (preg_match('/^(\d{3})([A-Z]{3})$/', $normalized, $matches)) {
            $variations[] = $matches[1] . '-' . $matches[2]; // 717-TPX
            $variations[] = $matches[1] . ' ' . $matches[2]; // 717 TPX
        }

        // Pattern: 3 letters + 3 digits (e.g., ABC123)
        if (preg_match('/^([A-Z]{3})(\d{3,4})$/', $normalized, $matches)) {
            $variations[] = $matches[1] . '-' . $matches[2]; // ABC-123
            $variations[] = $matches[1] . ' ' . $matches[2]; // ABC 123
        }

        // Pattern: letters + digits + letters (e.g., AB1234CD)
        if (preg_match('/^([A-Z]{1,3})(\d{1,4})([A-Z]{1,3})$/', $normalized, $matches)) {
            $variations[] = $matches[1] . '-' . $matches[2] . '-' . $matches[3];
            $variations[] = $matches[1] . ' ' . $matches[2] . ' ' . $matches[3];
        }

        return array_unique($variations);
    }

    /**
     * Check if two plate numbers are equivalent when normalized.
     *
     * @param string $plate1
     * @param string $plate2
     * @return bool
     */
    public static function arePlatesEquivalent(string $plate1, string $plate2): bool
    {
        return self::normalizePlateNumber($plate1) === self::normalizePlateNumber($plate2);
    }
}
