<?php

namespace App\Http\Resources;

use App\Helpers\ApplicationDisplayHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicantResource extends JsonResource
{

    protected $context;

    public function __construct($resource, $context = 'list')
    {
        parent::__construct($resource);
        $this->context = $context;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $baseData = [
            'id' => $this->id,
            'name' => $this->full_name,
            'email' => $this->email,
            'submitted_date' => $this->created_at->format('F d, Y'),
        ];

        if ($this->relationLoaded('details')) {
            $baseData = array_merge($baseData, [
                'clsu_id' => $this->details?->clsu_id ?? '-',
                'phone_number' => $this->phone_number,
                ...$this->when($this->details->status_name, [
                    'status' => [
                        'label' => $this->details->status_name
                    ]
                ])
            ]);
        }

        if (isset($this->vehicles_count)) {
            $baseData['vehicles'] = $this->vehicles_count;
        }

        if ($this->context === 'detail') {
            $baseData = array_merge($baseData, [
                $this->mergeWhen($this->relationLoaded('vehicles'), [
                    'vehicle_details' => $this->getVehicleDetails(),
                    'gate_pass_details' => $this->getGatePassDetails(),
                ]),

                $this->mergeWhen($this->relationLoaded('details'), [
                    'user_details' => $this->getDetailedUserInfo(),
                ]),
            ]);
        }

        return $baseData;
    }

    private function getDetailedUserInfo()
    {
        return [
            'name_initials' => $this->name_initial,
            'license_number' => $this->details?->license_number,
            'applicant_type' => $this->details?->applicant_type,
            'curr_address' => $this->details?->current_address,
            'city_municipality' => $this->details?->city_municipality,
            'province' => $this->details?->province,
            'postal_code' => $this->details?->postal_code,
            'country' => $this->details?->country,
            'status_badge' => $this->details?->status_badge,
        ];
    }

    private function getVehicleDetails()
    {
        return $this->vehicles->map(fn($vehicle) => [
            'plate_number' => $vehicle->license_plate,
            'vehicle_make_model' => $vehicle->vehicle_make_model,
            'vehicle_year' => $vehicle->vehicle_year,
            'registration_date' => $vehicle->created_at->format('F d, Y'),
        ]);
    }

    private function getGatePassDetails()
    {
        return $this->vehicles->map(fn($vehicle) => [
            'gate_pass' => $vehicle->assigned_gate_pass,
            'status' => $vehicle->status_badge,
            'date_issued' => $vehicle->created_at->format('F d, Y'),
            // expiry calculation to be added
            'expiry_date' => $vehicle->created_at->format('F d, Y'),
        ]);
    }

    public static function forList($resource)
    {
        return new self($resource, 'list');
    }

    public static function forDetail($resource)
    {
        return new self($resource, 'detail');
    }
}
