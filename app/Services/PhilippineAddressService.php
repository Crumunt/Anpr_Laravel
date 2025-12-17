<?php

namespace App\Services;

class PhilippineAddressService
{
    protected static $data = null;

    /**
     * Load the address data from JSON file
     */
    protected static function loadData(): array
    {
        if (self::$data !== null) {
            return self::$data;
        }

        $raw = file_get_contents(storage_path("app/json/cluster.json"));
        $decoded = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            \Log::error('JSON decode error: ' . json_last_error_msg());
            self::$data = [];
            return self::$data;
        }

        self::$data = $decoded ?? [];
        return self::$data;
    }

    /**
     * Get all regions with their codes
     */
    public static function getRegions(): array
    {
        $data = self::loadData();
        $regions = [];

        foreach ($data as $code => $regionData) {
            $regions[$code] = $regionData['region_name'] ?? $code;
        }

        return $regions;
    }

    /**
     * Get regions formatted for select dropdown
     */
    public static function getRegionsForSelect(): array
    {
        $regions = self::getRegions();
        $options = [];

        foreach ($regions as $code => $name) {
            $options[] = [
                'value' => $code,
                'label' => $name
            ];
        }

        return $options;
    }

    /**
     * Get region name by code
     */
    public static function getRegionName(string $code): ?string
    {
        $data = self::loadData();
        return $data[$code]['region_name'] ?? null;
    }

    /**
     * Get provinces by region code
     */
    public static function getProvinces(?string $regionCode = null): array
    {
        if (!$regionCode) {
            return [];
        }

        $data = self::loadData();

        if (!isset($data[$regionCode]['province_list'])) {
            return [];
        }

        return array_keys($data[$regionCode]['province_list']);
    }

    /**
     * Get provinces formatted for select dropdown
     */
    public static function getProvincesForSelect(?string $regionCode = null): array
    {
        $provinces = self::getProvinces($regionCode);
        return array_map(fn($province) => [
            'value' => $province,
            'label' => $province
        ], $provinces);
    }

    /**
     * Get municipalities by region code and province
     */
    public static function getMunicipalities(?string $regionCode = null, ?string $province = null): array
    {
        if (!$regionCode || !$province) {
            return [];
        }

        $data = self::loadData();

        if (!isset($data[$regionCode]['province_list'][$province]['municipality_list'])) {
            return [];
        }

        return array_keys($data[$regionCode]['province_list'][$province]['municipality_list']);
    }

    /**
     * Get municipalities formatted for select dropdown
     */
    public static function getMunicipalitiesForSelect(?string $regionCode = null, ?string $province = null): array
    {
        $municipalities = self::getMunicipalities($regionCode, $province);
        return array_map(fn($municipality) => [
            'value' => $municipality,
            'label' => $municipality
        ], $municipalities);
    }

    /**
     * Get barangays by region code, province, and municipality
     */
    public static function getBarangays(?string $regionCode = null, ?string $province = null, ?string $municipality = null): array
    {
        if (!$regionCode || !$province || !$municipality) {
            return [];
        }

        $data = self::loadData();

        if (!isset($data[$regionCode]['province_list'][$province]['municipality_list'][$municipality]['barangay_list'])) {
            return [];
        }

        return $data[$regionCode]['province_list'][$province]['municipality_list'][$municipality]['barangay_list'];
    }

    /**
     * Get barangays formatted for select dropdown
     */
    public static function getBarangaysForSelect(?string $regionCode = null, ?string $province = null, ?string $municipality = null): array
    {
        $barangays = self::getBarangays($regionCode, $province, $municipality);
        return array_map(fn($barangay) => [
            'value' => $barangay,
            'label' => $barangay
        ], $barangays);
    }

    /**
     * Validate if an address combination exists
     */
    public static function isValidAddress(string $regionCode, string $province, string $municipality, string $barangay): bool
    {
        $barangays = self::getBarangays($regionCode, $province, $municipality);
        return in_array($barangay, $barangays);
    }

    /**
     * Clear the cached data
     */
    public static function clearCache(): void
    {
        self::$data = null;
    }
}
