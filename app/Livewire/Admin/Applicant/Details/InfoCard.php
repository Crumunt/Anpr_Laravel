<?php

namespace App\Livewire\Admin\Applicant\Details;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Services\PhilippineAddressService;
use App\Models\User;
use App\Services\Admin\Applicants\ApplicantWriteService;

class InfoCard extends Component
{
    public string $cardTitle = "Card Title";
    public bool $canEdit = false;
    public string $modelName = "";
    public string $userId = "";
    public string $context = "default";

    public string $first_name;
    public string $middle_name;
    public string $last_name;
    public string $suffix;
    public string $email;
    public string $phone_number;
    public string $license_number;

    public $selectedRegion = null;
    public $selectedProvince = null;
    public $selectedMunicipality = null;
    public $selectedBarangay = null;
    public $zip_code = null;

    // service
    protected $applicantWriteService;

    protected $contextConfigs = [
        "address" => [
            "Region" => [
                "type" => "select",
                "required" => true,
            ],
            "Province" => [
                "type" => "select",
                "required" => true,
            ],
            "City/Municipality" => [
                "type" => "select",
                "required" => true,
            ],
            "Barangay" => [
                "type" => "select",
                "required" => true,
            ],
            "Postal Code" => [
                "type" => "text",
                "placeholder" => "e.g., 1000",
            ],
        ],
    ];

    public function boot(ApplicantWriteService $applicantWriteService)
    {
        $this->applicantWriteService = $applicantWriteService;
    }

    public function mount()
    {
        $user = User::with("details")->findOrFail($this->userId);
        $user->refresh();
        $fields = $user->getCardDetails($this->context);

        foreach (array_keys($fields) as $fieldName) {
            $isEmail = $fieldName === "email";
            $this->{$fieldName} = $isEmail
                ? $user->{$fieldName}
                : $user->details?->{$fieldName};
        }

        // Only load existing address values if context is address
        if ($this->context === "address") {
            $this->selectedRegion = $this->fieldValues["Region"] ?? null;
            $this->selectedProvince = $this->fieldValues["Province"] ?? null;
            $this->selectedMunicipality =
                $this->fieldValues["City/Municipality"] ?? null;
            $this->selectedBarangay = $this->fieldValues["Barangay"] ?? null;
            $this->zip_code = $this->fieldValues["Zip Code"] ?? null;
        }
    }

    protected function getFieldConfig($label)
    {
        if (isset($this->contextConfigs[$this->context][$label])) {
            $config = $this->contextConfigs[$this->context][$label];

            // Dynamically resolve options for address fields
            if ($this->context === "address") {
                $config["options"] = match ($label) {
                    "Region" => $this->getRegionOptions(),
                    "Province" => $this->getProvinceOptions(),
                    "City/Municipality" => $this->getMunicipalityOptions(),
                    "Barangay" => $this->getBarangayOptions(),
                    default => $config["options"] ?? [],
                };

                // Set wire:model for address fields
                $config["wire_model"] = match ($label) {
                    "Region" => "selectedRegion",
                    "Province" => "selectedProvince",
                    "City/Municipality" => "selectedMunicipality",
                    "Barangay" => "selectedBarangay",
                    default => null,
                };

                // Disable dependent fields if parent not selected
                $config["disabled"] = match ($label) {
                    "Province" => empty($this->provinces),
                    "City/Municipality" => empty($this->municipalities),
                    "Barangay" => empty($this->barangays),
                    default => false,
                };
            }
            return $config;
        }

        return [
            "type" => "text",
            "options" => [],
            "placeholder" => "",
            "required" => false,
            "disabled" => false,
            "wire_model" => null,
        ];
    }

    // Computed properties - only loaded when accessed
    public function getRegionsProperty()
    {
        if ($this->context !== "address") {
            return [];
        }
        return PhilippineAddressService::getRegions();
    }

    public function getProvincesProperty()
    {
        if ($this->context !== "address" || !$this->selectedRegion) {
            return [];
        }
        return PhilippineAddressService::getProvinces($this->selectedRegion);
    }

    public function getMunicipalitiesProperty()
    {
        if (
            $this->context !== "address" ||
            !$this->selectedRegion ||
            !$this->selectedProvince
        ) {
            return [];
        }
        return PhilippineAddressService::getMunicipalities(
            $this->selectedRegion,
            $this->selectedProvince,
        );
    }

    public function getBarangaysProperty()
    {
        if (
            $this->context !== "address" ||
            !$this->selectedRegion ||
            !$this->selectedProvince ||
            !$this->selectedMunicipality
        ) {
            return [];
        }
        return PhilippineAddressService::getBarangays(
            $this->selectedRegion,
            $this->selectedProvince,
            $this->selectedMunicipality,
        );
    }

    public function updatedSelectedRegion($value)
    {
        $this->selectedProvince = null;
        $this->selectedMunicipality = null;
        $this->selectedBarangay = null;

        if ($this->context === "address") {
            $this->dispatch("updateFormData", field: "Region", value: $value);
        }
    }

    public function updatedSelectedProvince($value)
    {
        $this->selectedMunicipality = null;
        $this->selectedBarangay = null;

        if ($this->context === "address") {
            $this->dispatch("updateFormData", field: "Province", value: $value);
        }
    }

    public function updatedSelectedMunicipality($value)
    {
        $this->selectedBarangay = null;

        if ($this->context === "address") {
            $this->dispatch(
                "updateFormData",
                field: "City/Municipality",
                value: $value,
            );
        }
    }

    public function getRegionOptions(): array
    {
        $options = [];
        foreach ($this->regions as $code => $name) {
            $options[] = [
                "value" => $code,
                "label" => $name,
            ];
        }
        return $options;
    }

    public function getProvinceOptions(): array
    {
        return array_map(
            fn($province) => [
                "value" => $province,
                "label" => $province,
            ],
            $this->provinces,
        );
    }

    public function getMunicipalityOptions(): array
    {
        return array_map(
            fn($municipality) => [
                "value" => $municipality,
                "label" => $municipality,
            ],
            $this->municipalities,
        );
    }

    public function getBarangayOptions(): array
    {
        return array_map(
            fn($barangay) => [
                "value" => $barangay,
                "label" => $barangay,
            ],
            $this->barangays,
        );
    }

    // Replace with a computed property
    #[Computed]
    public function fieldValues()
    {
        $user = User::with("details")->findOrFail($this->userId);
        $fields = $user->getCardDetails($this->context);
        $fieldValues = [];

        foreach ($fields as $fieldName => $label) {
            $isEmail = $fieldName === "email";

            // Use the current property value if set, otherwise fall back to database
            if ($this->context !== "address" && isset($this->{$fieldName})) {
                $value = $this->{$fieldName};
            } else {
                $value = $isEmail
                    ? $user->{$fieldName}
                    : $user->details?->{$fieldName};
            }

            $fieldValues[$label] = $value;
        }

        return $fieldValues;
    }

    public function save()
    {
        try {
            // Validate using context-specific rules
            $validated = $this->validate($this->getValidationRules());

            $validated["id"] = $this->userId;

            $this->applicantWriteService->update($validated, $this->context);

            //unset field values to reset the values displayed
            unset($this->fieldValues);

            // Dispatch success event
            $this->dispatch("save-success");
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation failed - format errors for display
            $errors = [];
            foreach ($e->errors() as $field => $messages) {
                $errors[] = implode("<br>", $messages);
            }

            $this->dispatch("save-failed", [
                "message" => implode("<br>", $errors),
            ]);

            // Re-throw so Livewire handles it properly
            throw $e;
        } catch (\Exception $e) {
            // Other errors (database, model not found, etc.)
            $this->dispatch("save-failed", [
                "message" => "Failed to save: " . $e->getMessage(),
            ]);

            // Log the error for debugging
            \Log::error("Save failed", [
                "model" => "User",
                "id" => $this->userId ?? null,
                "error" => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    // Base rules - direct property names matching wire:model
    protected $rules = [
        // Personal / Profile Info
        "first_name" => "required|string|max:255",
        "middle_name" => "nullable|string|max:255",
        "last_name" => "required|string|max:255",
        "suffix" => "nullable|string|max:10",
        "phone_number" => "required|string|max:20",
        "email" => "required|email",
        "license_number" => "nullable|string",
        // Location
        "selectedRegion" => "required|string",
        "selectedProvince" => "required|string",
        "selectedMunicipality" => "required|string",
        "selectedBarangay" => "required|string",
        "zip_code" => "required|string|digits:4",
    ];

    private function getValidationRules()
    {
        $baseRules = match ($this->context) {
            "default" => array_intersect_key(
                $this->rules,
                array_flip([
                    "first_name",
                    "middle_name",
                    "last_name",
                    "suffix",
                    "phone_number",
                    "email",
                    "license_number",
                ]),
            ),
            "address" => array_intersect_key(
                $this->rules,
                array_flip([
                    "selectedRegion",
                    "selectedProvince",
                    "selectedMunicipality",
                    "selectedBarangay",
                    "zip_code",
                ]),
            ),
            default => [],
        };

        // Handle dynamic email unique rule
        if (isset($baseRules["email"])) {
            $baseRules["email"] .=
                "|unique:users,email," . ($this->userId ?? "NULL");
        }

        if (isset($baseRule["license_number"])) {
            $baseRules["license_number"] .=
                "|unique:user_details,license_number," .
                ($this->userId ?? "NULL");
        }

        return $baseRules;
    }

    public function render()
    {
        return view("livewire.admin.applicant.details.info-card");
    }
}
