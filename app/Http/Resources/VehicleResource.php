<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Status;

class VehicleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Get application status code (lowercase) for conditional rendering
        $applicationStatus = $this->application?->status?->code ?? 'pending';

        return [
            "vehicle_id" => $this->id,
            "vehicle_info" => $this->vehicle_info,
            "plate_number" => $this->plate_number,
            "gate_pass_number" => $this->assigned_gate_pass ?? 'Not yet assigned',
            "previous_gate_pass" => $this->previous_gate_pass,
            "gate_pass_assignment_count" => $this->gate_pass_assignment_count ?? 0,
            "registration_date" => $this->created_at->format("F d, Y"),
            "application_status" => $applicationStatus,
            // Expiration info
            "approved_at" => $this->approved_at?->format("F d, Y"),
            "expires_at" => $this->expires_at?->format("F d, Y"),
            "validity_years" => $this->validity_years,
            "days_until_expiration" => $this->days_until_expiration,
            "is_expired" => $this->isExpired(),
            "is_expiring_soon" => $this->isExpiringSoon(),
            "expiration_status" => $this->expiration_status,
            // Renewal info
            "is_renewal" => $this->is_renewal,
            "renewed_from_vehicle_id" => $this->renewed_from_vehicle_id,
            "renewal_requested_at" => $this->renewal_requested_at?->format("F d, Y"),
            "can_renew" => $this->canRenew(),
        ];
    }
}
