<?php

namespace App\Traits;

use App\Services\PhilippineAddressService;

trait HasPhilippineAddress
{
    public $selectedRegion; // This will store the region code (e.g., "01")
    public $selectedProvince;
    public $selectedMunicipality;
    public $selectedBarangay;

    public $regions = [];
    public $provinces = [];
    public $municipalities = [];
    public $barangays = [];

    protected $dispatchAddressEvents = false;

    public function initializeHasPhilippineAddress()
    {
        // Get regions as associative array: code => name
        $this->regions = PhilippineAddressService::getRegions();
    }

    public function updatedSelectedRegion($value)
    {
        $this->provinces = PhilippineAddressService::getProvinces($value);
        $this->selectedProvince = null;
        $this->municipalities = [];
        $this->selectedMunicipality = null;
        $this->barangays = [];
        $this->selectedBarangay = null;

        if ($this->dispatchAddressEvents) {
            $this->dispatch('updateFormData', field: 'Region', value: $value);
        }
    }

    public function updatedSelectedProvince($value)
    {
        $this->municipalities = PhilippineAddressService::getMunicipalities(
            $this->selectedRegion,
            $value
        );
        $this->selectedMunicipality = null;
        $this->barangays = [];
        $this->selectedBarangay = null;

        if ($this->dispatchAddressEvents) {
            $this->dispatch('updateFormData', field: 'Province', value: $value);
        }
    }

    public function updatedSelectedMunicipality($value)
    {
        $this->barangays = PhilippineAddressService::getBarangays(
            $this->selectedRegion,
            $this->selectedProvince,
            $value
        );
        $this->selectedBarangay = null;

        if ($this->dispatchAddressEvents) {
            $this->dispatch('updateFormData', field: 'City/Municipality', value: $value);
        }
    }

    /**
     * Load existing address data (useful for edit forms)
     */
    public function loadAddress($regionCode, $province, $municipality, $barangay)
    {
        if (!$regionCode) return;

        $this->selectedRegion = $regionCode;
        $this->provinces = PhilippineAddressService::getProvinces($regionCode);

        if ($province) {
            $this->selectedProvince = $province;
            $this->municipalities = PhilippineAddressService::getMunicipalities($regionCode, $province);
        }

        if ($municipality) {
            $this->selectedMunicipality = $municipality;
            $this->barangays = PhilippineAddressService::getBarangays($regionCode, $province, $municipality);
        }

        if ($barangay) {
            $this->selectedBarangay = $barangay;
        }
    }

    /**
     * Get options formatted for select dropdowns
     */
    public function getRegionOptions(): array
    {
        $options = [];
        foreach ($this->regions as $code => $name) {
            $options[] = [
                'value' => $code,
                'label' => $name
            ];
        }
        return $options;
    }

    public function getProvinceOptions(): array
    {
        return array_map(fn($province) => [
            'value' => $province,
            'label' => $province
        ], $this->provinces);
    }

    public function getMunicipalityOptions(): array
    {
        return array_map(fn($municipality) => [
            'value' => $municipality,
            'label' => $municipality
        ], $this->municipalities);
    }

    public function getBarangayOptions(): array
    {
        return array_map(fn($barangay) => [
            'value' => $barangay,
            'label' => $barangay
        ], $this->barangays);
    }

    /**
     * Get the full address display
     */
    public function getFullAddress(): string
    {
        $parts = [];

        if ($this->selectedBarangay) {
            $parts[] = $this->selectedBarangay;
        }

        if ($this->selectedMunicipality) {
            $parts[] = $this->selectedMunicipality;
        }

        if ($this->selectedProvince) {
            $parts[] = $this->selectedProvince;
        }

        if ($this->selectedRegion) {
            $regionName = PhilippineAddressService::getRegionName($this->selectedRegion);
            if ($regionName) {
                $parts[] = $regionName;
            }
        }

        return implode(', ', $parts);
    }
}
